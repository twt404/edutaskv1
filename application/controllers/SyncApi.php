<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SyncApi extends CI_Controller {

    private $secretKey = 'SECRET_SYNC_KEY_12345'; // Ganti dengan secret key unik

    public function __construct() {
        parent::__construct();
        $this->load->library('syncManager');
    }

    private function validateAuth() {
        $token = $this->input->get_post('auth_key');
        if ($token !== $this->secretKey) {
            http_response_code(403);
            echo json_encode(['status' => false, 'message' => 'Unauthorized Access']);
            exit;
        }
    }

    /**
     * Endpoint untuk trigger generate package baru di Hosting
     */
    public function export() {
        $this->validateAuth();

        try {
            // Tentukan folder asset yang mau diikutkan
            $zipPath = $this->syncmanager->exportPackage(['uploads']);
            $fileName = basename($zipPath);

            echo json_encode([
                'status' => true,
                'message' => 'Export Success',
                'download_url' => site_url('syncApi/download?file=' . $fileName . '&auth_key=' . $this->secretKey)
            ]);
        } catch (Exception $e) {
            echo json_encode(['status' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Endpoint untuk menerima upload package ZIP dari Lokal (Push mode)
     */
    public function import() {
        $this->validateAuth();

        if (!empty($_FILES['sync_file']['name'])) {
            $config['upload_path']   = FCPATH . 'uploads/sync_backups/';
            $config['allowed_types'] = 'zip';
            $config['max_size']      = 512000; // 500MB
            $config['file_name']     = 'imported_' . time() . '.zip';

            if (!is_dir($config['upload_path'])) {
                mkdir($config['upload_path'], 0777, true);
            }

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('sync_file')) {
                $uploadData = $this->upload->data();
                $zipPath = $uploadData['full_path'];

                try {
                    $result = $this->syncmanager->importPackage($zipPath);
                    @unlink($zipPath);

                    if ($result) {
                        echo json_encode(['status' => true, 'message' => 'Import & Restore Success on Remote/Hosting']);
                    } else {
                        echo json_encode(['status' => false, 'message' => 'Failed to extract or restore package']);
                    }
                } catch (Exception $e) {
                    @unlink($zipPath);
                    echo json_encode(['status' => false, 'message' => 'Exception during restore: ' . $e->getMessage()]);
                }
            } else {
                echo json_encode(['status' => false, 'message' => $this->upload->display_errors('', '')]);
            }
        } else {
            echo json_encode(['status' => false, 'message' => 'No file uploaded']);
        }
    }

    /**
     * Endpoint untuk stream download file ZIP dari Hosting ke Lokal
     */
    public function download() {
        $this->validateAuth();

        $fileName = $this->input->get('file');
        $filePath = FCPATH . 'uploads/sync_backups/' . basename($fileName);

        if (file_exists($filePath)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/zip');
            header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($filePath));
            readfile($filePath);
            
            // Hapus file backup setelah didownload agar hemat disk
            @unlink($filePath);
            exit;
        } else {
            show_404();
        }
    }
}
