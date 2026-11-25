<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SyncManager {

    protected $CI;
    protected $backupPath;

    public function __construct() {
        $this->CI =& get_instance();
        $this->CI->load->dbutil();
        $this->CI->load->helper('file');

        $this->backupPath = FCPATH . 'uploads/sync_backups/';
        if (!is_dir($this->backupPath)) {
            mkdir($this->backupPath, 0777, true);
        }
    }

    /**
     * Export database dan folder uploads ke file ZIP tunggal
     * 
     * @param array $includeFolders Array nama folder dalam FCPATH/uploads untuk di-zip
     * @return string Path ke file ZIP hasil backup
     */
    public function exportPackage($includeFolders = ['uploads']) {
        set_time_limit(0);
        ini_set('memory_limit', '512M');

        $timestamp = date('Ymd_His');
        $sqlFileName = 'db_dump_' . $timestamp . '.sql';
        $sqlFilePath = $this->backupPath . $sqlFileName;

        // 1. Export DB
        $prefs = array(
            'format'      => 'txt',
            'filename'    => $sqlFileName,
            'add_drop'    => TRUE,
            'add_insert'  => TRUE,
            'newline'     => "\n"
        );
        $backupData = $this->CI->dbutil->backup($prefs);
        write_file($sqlFilePath, $backupData);

        // 2. Packaging to ZIP
        $zipFileName = 'sync_package_' . $timestamp . '.zip';
        $zipFilePath = $this->backupPath . $zipFileName;

        $zip = new ZipArchive();
        if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            // Add SQL File
            $zip->addFile($sqlFilePath, 'database.sql');

            // Add Assets Folders
            foreach ($includeFolders as $folder) {
                $targetDir = FCPATH . trim($folder, '/');
                if (is_dir($targetDir)) {
                    $this->addFolderToZip($targetDir, $zip, $folder);
                }
            }
            $zip->close();
        }

        // Hapus dump SQL sementara
        if (file_exists($sqlFilePath)) {
            @unlink($sqlFilePath);
        }

        return $zipFilePath;
    }

    /**
     * Import file ZIP package (Restore DB & Assets)
     * 
     * @param string $zipFilePath Path ke file ZIP yang sudah didownload
     * @return bool Status berhasil/gagal
     */
    public function importPackage($zipFilePath) {
        if (!file_exists($zipFilePath)) {
            return false;
        }

        set_time_limit(0);
        ini_set('memory_limit', '512M');

        $extractPath = $this->backupPath . 'extracted_' . time() . '/';
        if (!is_dir($extractPath)) {
            mkdir($extractPath, 0777, true);
        }

        $zip = new ZipArchive();
        if ($zip->open($zipFilePath) === TRUE) {
            $zip->extractTo($extractPath);
            $zip->close();
        } else {
            return false;
        }

        // 1. Restore DB jika ada
        $sqlFile = $extractPath . 'database.sql';
        if (file_exists($sqlFile)) {
            $this->runSqlImport($sqlFile);
        }

        // 2. Restore Files/Assets
        $this->copyDirectory($extractPath, FCPATH);

        // Cleanup temp extracted folder
        $this->deleteDirectory($extractPath);

        return true;
    }

    /**
     * Pull data dari Remote (Hosting) ke Local:
     * 1. Call export endpoint di remote
     * 2. Download ZIP package
     * 3. Extract & Restore ke lokal
     */
    public function pullFromRemote($remoteUrl, $secretKey) {
        set_time_limit(0);
        ini_set('memory_limit', '512M');

        $exportUrl = rtrim($remoteUrl, '/') . '/syncApi/export?auth_key=' . urlencode($secretKey);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $exportUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 300);
        $response = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

        if ($err) {
            throw new Exception('cURL Error: ' . $err);
        }

        $resData = json_decode($response, true);
        if (!$resData || empty($resData['status']) || empty($resData['download_url'])) {
            $msg = isset($resData['message']) ? $resData['message'] : 'Gagal menghubungi server remote.';
            throw new Exception($msg);
        }

        // Bangun ulang URL download memakai base yang sama dengan export (dijamin benar)
        parse_str(parse_url($resData['download_url'], PHP_URL_QUERY), $query);
        $fileName = isset($query['file']) ? $query['file'] : '';
        $downloadUrl = rtrim($remoteUrl, '/') . '/syncApi/download?file=' . urlencode($fileName) . '&auth_key=' . urlencode($secretKey);

        $tempZip = $this->backupPath . 'pulled_' . time() . '.zip';

        $fp = fopen($tempZip, 'w+');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $downloadUrl);
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 600);
        curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);
        fclose($fp);

        if ($err || !file_exists($tempZip) || filesize($tempZip) == 0) {
            @unlink($tempZip);
            throw new Exception('Gagal mendownload package backup dari remote: ' . $err);
        }

        // Validasi file benar-benar ZIP (hindari HTML 404 yang tertulis sebagai file)
        if (file_get_contents($tempZip, false, null, 0, 2) !== 'PK') {
            @unlink($tempZip);
            throw new Exception('File yang didownload bukan ZIP valid (kemungkinan URL download 404/HTML). Periksa URL remote/index_page.');
        }

        // Restore
        $restored = $this->importPackage($tempZip);
        @unlink($tempZip);

        if (!$restored) {
            throw new Exception('Gagal mengekstrak atau merestore database/assets dari package.');
        }

        $this->writeSyncLog('PULL (Hosting -> Lokal)', 'SUCCESS', 'Berhasil menarik database dan file assets.');
        return true;
    }

    /**
     * Push data dari Local ke Remote (Hosting):
     * 1. Export package lokal
     * 2. Upload (POST cURL) ke import endpoint remote
     * 3. Remote ekstrak & restore otomatis
     */
    public function pushToRemote($remoteUrl, $secretKey) {
        set_time_limit(0);
        ini_set('memory_limit', '512M');

        // Export local package
        $zipFilePath = $this->exportPackage(['uploads']);
        if (!file_exists($zipFilePath)) {
            throw new Exception('Gagal membuat package backup lokal.');
        }

        $importUrl = rtrim($remoteUrl, '/') . '/syncApi/import?auth_key=' . urlencode($secretKey);

        $cFile = new CURLFile($zipFilePath, 'application/zip', basename($zipFilePath));
        $postData = ['sync_file' => $cFile];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $importUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 600);
        $response = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

        @unlink($zipFilePath);

        if ($err) {
            throw new Exception('cURL Upload Error: ' . $err);
        }

        $resData = json_decode($response, true);
        if (!$resData || empty($resData['status'])) {
            $msg = !empty($response) ? 'Response: ' . strip_tags($response) : (isset($resData['message']) ? $resData['message'] : 'Gagal melakukan restore pada server remote.');
            throw new Exception($msg);
        }

        $this->writeSyncLog('PUSH (Lokal -> Hosting)', 'SUCCESS', 'Berhasil mengirim database dan file assets ke hosting.');
        return true;
    }

    private function writeSyncLog($type, $status, $message) {
        $logFile = $this->backupPath . 'sync_info.json';
        $data = [
            'timestamp' => date('Y-m-d H:i:s'),
            'type'      => $type,
            'status'    => $status,
            'message'   => $message
        ];
        file_put_contents($logFile, json_encode($data, JSON_PRETTY_PRINT));
    }

    public function getSyncInfo() {
        $logFile = $this->backupPath . 'sync_info.json';
        if (file_exists($logFile)) {
            return json_decode(file_get_contents($logFile), true);
        }
        return null;
    }

    private function addFolderToZip($folderPath, &$zip, $localPath = '') {
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($folderPath, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($files as $file) {
            $filePath = $file->getRealPath();
            $relativePath = $localPath . '/' . substr($filePath, strlen($folderPath) + 1);
            $relativePath = str_replace('\\', '/', $relativePath);

            // Skip folder sync_backups agar tidak looping package
            if (strpos($relativePath, 'sync_backups') !== false) {
                continue;
            }

            if ($file->isDir()) {
                $zip->addEmptyDir($relativePath);
            } else if ($file->isFile()) {
                $zip->addFile($filePath, $relativePath);
            }
        }
    }

    private function runSqlImport($sqlFilePath) {
        $queries = file_get_contents($sqlFilePath);
        $sqls = explode(";\n", $queries);
        
        $this->CI->db->query("SET FOREIGN_KEY_CHECKS = 0;");
        $this->CI->db->trans_start();
        foreach ($sqls as $sql) {
            $sql = trim($sql);
            if (!empty($sql)) {
                $this->CI->db->query($sql);
            }
        }
        $this->CI->db->trans_complete();
        $this->CI->db->query("SET FOREIGN_KEY_CHECKS = 1;");
    }

    private function copyDirectory($src, $dst) {
        $dir = opendir($src);
        @mkdir($dst);
        while (false !== ($file = readdir($dir))) {
            if (($file != '.') && ($file != '..') && ($file != 'database.sql')) {
                if (is_dir($src . '/' . $file)) {
                    $this->copyDirectory($src . '/' . $file, $dst . '/' . $file);
                } else {
                    copy($src . '/' . $file, $dst . '/' . $file);
                }
            }
        }
        closedir($dir);
    }

    private function deleteDirectory($dir) {
        if (!file_exists($dir)) return true;
        if (!is_dir($dir)) return unlink($dir);
        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') continue;
            if (!$this->deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) return false;
        }
        return rmdir($dir);
    }
}
