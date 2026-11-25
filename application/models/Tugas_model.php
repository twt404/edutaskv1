<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tugas_model extends CI_Model {

    protected $table             = 'tugas';
    protected $pengumpulan_table = 'pengumpulan_tugas';

    public function __construct()
    {
        parent::__construct();
    }

    // -----------------------------------------------------------------------
    // TUGAS CRUD
    // -----------------------------------------------------------------------

    /**
     * Get all tugas for a given mapel.
     */
    public function get_by_mapel($mapel_id)
    {
        return $this->db
            ->where('mapel_id', $mapel_id)
            ->order_by('created_at', 'DESC')
            ->get($this->table)
            ->result();
    }

    /**
     * Get a single tugas by ID.
     */
    public function get_by_id($id)
    {
        return $this->db
            ->where('id', $id)
            ->get($this->table)
            ->row();
    }

    /**
     * Get tugas with the count of pengumpulan (submissions).
     */
    public function get_with_submissions_count($tugas_id)
    {
        return $this->db
            ->select('tugas.*, COUNT(pt.id) AS jumlah_pengumpulan')
            ->from($this->table)
            ->join($this->pengumpulan_table . ' pt', 'pt.tugas_id = tugas.id', 'left')
            ->where('tugas.id', $tugas_id)
            ->group_by('tugas.id')
            ->get()
            ->row();
    }

    /**
     * Insert a new tugas.
     */
    public function insert($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    /**
     * Update a tugas.
     */
    public function update($id, $data)
    {
        $this->db->where('id', $id)->update($this->table, $data);
        return $this->db->affected_rows();
    }

    /**
     * Delete a tugas (cascade-safe: submissions reference it via FK).
     */
    public function delete($id)
    {
        // Delete file per item, submissions, items, then the tugas itself.
        $this->db->where('tugas_id', $id)->delete('notifikasi');
        $this->db
            ->where('pengumpulan_id IN (SELECT id FROM ' . $this->pengumpulan_table . ' WHERE tugas_id = ' . (int) $id . ')', NULL, FALSE)
            ->delete('pengumpulan_tugas_file');
        $this->db->where('tugas_id', $id)->delete($this->pengumpulan_table);
        $this->db->where('tugas_id', $id)->delete('tugas_item');
        $this->db->where('id', $id)->delete($this->table);
        return $this->db->affected_rows();
    }

    // -----------------------------------------------------------------------
    // ALL TUGAS FOR A GURU (across mapel)
    // -----------------------------------------------------------------------

    /**
     * Get all tugas from every mapel owned by a guru, with submission counts.
     */
    public function get_all_by_guru($guru_id)
    {
        return $this->db
            ->select('tugas.*, mapel.nama_mapel, kelas.nama_kelas, COUNT(pt.id) AS jumlah_pengumpulan')
            ->from($this->table)
            ->join('mapel', 'mapel.id = tugas.mapel_id')
            ->join('kelas', 'kelas.id = mapel.kelas_id', 'left')
            ->join($this->pengumpulan_table . ' pt', 'pt.tugas_id = tugas.id', 'left')
            ->where('mapel.guru_id', $guru_id)
            ->group_by('tugas.id')
            ->order_by('tugas.created_at', 'DESC')
            ->get()
            ->result();
    }

    // -----------------------------------------------------------------------
    // PENGUMPULAN (SUBMISSIONS)
    // -----------------------------------------------------------------------

    /**
     * Get all submissions for a tugas, joined with student name.
     */
    public function get_pengumpulan_by_tugas($tugas_id)
    {
        return $this->db
            ->select('pt.*, u.nama AS nama_siswa, u.email AS email_siswa, u.foto AS foto_siswa, k.nama_kelompok')
            ->from($this->pengumpulan_table . ' pt')
            ->join('users u', 'u.id = pt.siswa_id')
            ->join('kelompok k', 'k.id = pt.kelompok_id', 'left')
            ->where('pt.tugas_id', $tugas_id)
            ->order_by('pt.submitted_at', 'DESC')
            ->get()
            ->result();
    }

    // -----------------------------------------------------------------------
    // PENGUMPULAN (SUBMISSIONS)
    // -----------------------------------------------------------------------

    public function get_penerima($tugas_id)
    {
        return $this->db
            ->select('tp.*, u.nama, u.email')
            ->from('tugas_penerima tp')
            ->join('users u', 'u.id = tp.siswa_id')
            ->where('tp.tugas_id', $tugas_id)
            ->order_by('u.nama', 'ASC')
            ->get()
            ->result();
    }

    public function save_penerima($tugas_id, $siswa_ids)
    {
        $this->db->where('tugas_id', $tugas_id)->delete('tugas_penerima');
        if ( ! empty($siswa_ids)) {
            $data = array();
            foreach ($siswa_ids as $siswa_id) {
                $data[] = array('tugas_id' => $tugas_id, 'siswa_id' => $siswa_id);
            }
            $this->db->insert_batch('tugas_penerima', $data);
        }
    }

    public function get_lampiran($tugas_id)
    {
        return $this->db
            ->where('tugas_id', $tugas_id)
            ->order_by('id', 'ASC')
            ->get('tugas_lampiran')
            ->result();
    }

    public function get_lampiran_by_id($id)
    {
        return $this->db->where('id', $id)->get('tugas_lampiran')->row();
    }

    public function save_lampiran($tugas_id, $nama_file, $nama_asli, $ukuran)
    {
        return $this->db->insert('tugas_lampiran', array(
            'tugas_id'    => $tugas_id,
            'nama_file'   => $nama_file,
            'nama_asli'   => $nama_asli,
            'ukuran_file' => $ukuran
        ));
    }

    public function delete_lampiran($id)
    {
        $lampiran = $this->get_lampiran_by_id($id);
        if ($lampiran) {
            @unlink(FCPATH . 'uploads/tugas/' . $lampiran->nama_file);
            $this->db->where('id', $id)->delete('tugas_lampiran');
            return TRUE;
        }
        return FALSE;
    }

    public function get_pengumpulan_by_id($pengumpulan_id)
    {
        return $this->db
            ->where('id', $pengumpulan_id)
            ->get($this->pengumpulan_table)
            ->row();
    }

    public function get_pengumpulan_by_kelompok($tugas_id, $kelompok_id)
    {
        if ( ! $kelompok_id) {
            return NULL;
        }

        return $this->db
            ->where('tugas_id', $tugas_id)
            ->where('kelompok_id', $kelompok_id)
            ->get($this->pengumpulan_table)
            ->row();
    }

    /**
     * Ambil pengumpulan yang relevan untuk seorang siswa: miliknya sendiri
     * (tugas individu) atau milik kelompoknya (tugas kelompok).
     */
    public function get_pengumpulan_for_student($tugas_id, $siswa_id)
    {
        $sql = 'SELECT p.*
                FROM pengumpulan_tugas p
                WHERE p.tugas_id = ?
                  AND (
                    p.siswa_id = ?
                    OR p.kelompok_id IN (
                      SELECT ka.kelompok_id
                      FROM kelompok_anggota ka
                      JOIN kelompok k ON k.id = ka.kelompok_id
                      WHERE ka.siswa_id = ? AND k.tugas_id = ?
                    )
                  )
                ORDER BY p.id DESC
                LIMIT 1';

        return $this->db->query($sql, array($tugas_id, $siswa_id, $siswa_id, $tugas_id))->row();
    }

    public function insert_pengumpulan($data)
    {
        $this->db->insert($this->pengumpulan_table, $data);
        return $this->db->insert_id();
    }

    public function update_pengumpulan($id, $data)
    {
        $this->db->where('id', $id)->update($this->pengumpulan_table, $data);
        return $this->db->affected_rows();
    }

    public function count_pengumpulan_by_tugas($tugas_id)
    {
        return (int) $this->db
            ->where('tugas_id', $tugas_id)
            ->count_all_results($this->pengumpulan_table);
    }

    /**
     * Hapus satu pengumpulan beserta baris file dan berkas fisiknya.
     */
    public function delete_pengumpulan($pengumpulan_id)
    {
        $pengumpulan = $this->get_pengumpulan_by_id($pengumpulan_id);
        if ( ! $pengumpulan) {
            return FALSE;
        }

        $files = $this->db
            ->where('pengumpulan_id', $pengumpulan_id)
            ->get('pengumpulan_tugas_file')
            ->result();

        $nama_files = array();
        foreach ($files as $file) {
            if ( ! empty($file->nama_file)) {
                $nama_files[$file->nama_file] = TRUE;
            }
        }
        if ( ! empty($pengumpulan->file_jawaban)) {
            $nama_files[$pengumpulan->file_jawaban] = TRUE;
        }

        $this->db->trans_start();
        $this->db->where('pengumpulan_id', $pengumpulan_id)->delete('notifikasi');
        $this->db->where('pengumpulan_id', $pengumpulan_id)->delete('pengumpulan_tugas_file');
        $this->db->where('id', $pengumpulan_id)->delete($this->pengumpulan_table);
        $this->db->trans_complete();

        if ( ! $this->db->trans_status()) {
            return FALSE;
        }

        foreach (array_keys($nama_files) as $nama_file) {
            $path = FCPATH . 'uploads/jawaban/' . $nama_file;
            if (is_file($path)) {
                @unlink($path);
            }
        }

        return TRUE;
    }

    /**
     * Get a specific submission for a siswa on a tugas.
     */
    public function get_pengumpulan_by_siswa($tugas_id, $siswa_id)
    {
        return $this->db
            ->where('tugas_id', $tugas_id)
            ->where('siswa_id', $siswa_id)
            ->get($this->pengumpulan_table)
            ->row();
    }

    /**
     * Give a grade (nilai) and feedback for a submission.
     * Updates status to 'dinilai' and records dinilai_at timestamp.
     */
    public function beri_nilai($pengumpulan_id, $nilai, $feedback)
    {
        $this->db->where('id', $pengumpulan_id)->update($this->pengumpulan_table, [
            'nilai'      => $nilai,
            'feedback'   => $feedback,
            'status'     => 'dinilai',
            'dinilai_at' => date('Y-m-d H:i:s'),
        ]);
        return $this->db->affected_rows();
    }

    public function minta_revisi($pengumpulan_id, $feedback)
    {
        $pengumpulan = $this->get_pengumpulan_by_id($pengumpulan_id);
        if ( ! $pengumpulan) {
            return FALSE;
        }

        $data = array(
            'nilai'      => NULL,
            'feedback'   => $feedback,
            'status'     => 'revisi',
            'dinilai_at' => NULL,
        );

        if ($pengumpulan->status !== 'revisi' && $pengumpulan->nilai !== NULL) {
            $data['nilai_sebelumnya']      = $pengumpulan->nilai;
            $data['feedback_sebelumnya']   = $pengumpulan->feedback;
            $data['dinilai_at_sebelumnya'] = $pengumpulan->dinilai_at;
        }

        $this->db->where('id', $pengumpulan_id);
        return $this->db->update($this->pengumpulan_table, $data);
    }

    // -----------------------------------------------------------------------
    // ITEM TUGAS (CHECKLIST DARI GURU)
    // -----------------------------------------------------------------------

    /**
     * Daftar poin/berkas yang diminta guru untuk sebuah tugas.
     */
    public function get_items($tugas_id)
    {
        return $this->db
            ->where('tugas_id', $tugas_id)
            ->order_by('urutan', 'ASC')
            ->order_by('id', 'ASC')
            ->get('tugas_item')
            ->result();
    }

    /**
     * Sinkronkan daftar item tugas dari form guru.
     *
     * $items = array of ['id' => int, 'label' => string, 'instruksi' => string]
     */
    public function sync_items($tugas_id, $items)
    {
        $this->db->trans_start();

        $kept    = array();
        $urutan  = 1;

        foreach ($items as $item) {
            $label = trim((string) (isset($item['label']) ? $item['label'] : ''));
            if ($label === '') {
                continue;
            }

            $instruksi = trim((string) (isset($item['instruksi']) ? $item['instruksi'] : ''));
            $id        = isset($item['id']) ? (int) $item['id'] : 0;

            if ($id > 0) {
                $exists = $this->db
                    ->where('id', $id)
                    ->where('tugas_id', $tugas_id)
                    ->count_all_results('tugas_item') > 0;
            } else {
                $exists = FALSE;
            }

            $data = array(
                'label'     => $label,
                'instruksi' => $instruksi !== '' ? $instruksi : NULL,
                'urutan'    => $urutan,
            );

            if ($exists) {
                $this->db->where('id', $id)->update('tugas_item', $data);
                $kept[] = $id;
            } else {
                $data['tugas_id']  = $tugas_id;
                $data['created_at'] = date('Y-m-d H:i:s');
                $this->db->insert('tugas_item', $data);
                $kept[] = $this->db->insert_id();
            }

            $urutan++;
        }

        $this->db->where('tugas_id', $tugas_id);
        if ( ! empty($kept)) {
            $this->db->where_not_in('id', $kept);
        }
        $this->db->delete('tugas_item');

        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    // -----------------------------------------------------------------------
    // FILE PENGUMPULAN PER ITEM
    // -----------------------------------------------------------------------

    public function get_files_by_pengumpulan($pengumpulan_id)
    {
        return $this->db
            ->select('f.*, ti.label, ti.instruksi, ti.urutan')
            ->from('pengumpulan_tugas_file f')
            ->join('tugas_item ti', 'ti.id = f.tugas_item_id', 'left')
            ->where('f.pengumpulan_id', $pengumpulan_id)
            ->order_by('ti.urutan', 'ASC')
            ->order_by('f.id', 'ASC')
            ->get()
            ->result();
    }

    public function get_files_by_tugas($tugas_id)
    {
        return $this->db
            ->select('f.*, ti.label, ti.instruksi, ti.urutan')
            ->from('pengumpulan_tugas_file f')
            ->join($this->pengumpulan_table . ' pt', 'pt.id = f.pengumpulan_id')
            ->join('tugas_item ti', 'ti.id = f.tugas_item_id', 'left')
            ->where('pt.tugas_id', $tugas_id)
            ->order_by('ti.urutan', 'ASC')
            ->order_by('f.id', 'ASC')
            ->get()
            ->result();
    }

    public function get_file_by_item($pengumpulan_id, $tugas_item_id)
    {
        return $this->db
            ->where('pengumpulan_id', $pengumpulan_id)
            ->where('tugas_item_id', $tugas_item_id)
            ->get('pengumpulan_tugas_file')
            ->row();
    }

    public function get_files_by_pengumpulan_item($pengumpulan_id, $tugas_item_id)
    {
        return $this->db
            ->where('pengumpulan_id', $pengumpulan_id)
            ->where('tugas_item_id', $tugas_item_id)
            ->order_by('id', 'ASC')
            ->get('pengumpulan_tugas_file')
            ->result();
    }

    public function get_files_without_item($pengumpulan_id)
    {
        return $this->db
            ->where('pengumpulan_id', $pengumpulan_id)
            ->where('tugas_item_id IS NULL', NULL, FALSE)
            ->order_by('id', 'ASC')
            ->get('pengumpulan_tugas_file')
            ->result();
    }

    public function add_file($pengumpulan_id, $tugas_item_id, $nama_file, $nama_asli = NULL, $ukuran = 0)
    {
        $this->db->insert('pengumpulan_tugas_file', array(
            'pengumpulan_id' => $pengumpulan_id,
            'tugas_item_id'  => $tugas_item_id,
            'nama_file'      => $nama_file,
            'nama_asli'      => $nama_asli,
            'ukuran_file'    => (int) $ukuran,
            'created_at'     => date('Y-m-d H:i:s'),
        ));
        return $this->db->insert_id();
    }

    public function delete_file_by_id($id)
    {
        $file = $this->db->where('id', $id)->get('pengumpulan_tugas_file')->row();
        if ( ! $file) {
            return FALSE;
        }

        @unlink(FCPATH . 'uploads/jawaban/' . $file->nama_file);
        $this->db->where('id', $id)->delete('pengumpulan_tugas_file');
        return TRUE;
    }

    public function save_file($pengumpulan_id, $tugas_item_id, $nama_file)
    {
        $existing = $this->get_file_by_item($pengumpulan_id, $tugas_item_id);

        if ($existing) {
            $this->db->where('id', $existing->id)->update('pengumpulan_tugas_file', array(
                'nama_file' => $nama_file,
            ));
            return $existing->id;
        }

        $this->db->insert('pengumpulan_tugas_file', array(
            'pengumpulan_id' => $pengumpulan_id,
            'tugas_item_id'  => $tugas_item_id,
            'nama_file'      => $nama_file,
            'created_at'     => date('Y-m-d H:i:s'),
        ));
        return $this->db->insert_id();
    }

    // -----------------------------------------------------------------------
    // HELPERS FOR DASHBOARD
    // -----------------------------------------------------------------------

    /**
     * Get recent submissions (menunggu/terlambat) for guru's mapel, used in dashboard.
     */
    public function get_recent_pengumpulan_for_guru($guru_id, $limit = 10)
    {
        return $this->db
            ->select('pt.*, u.nama AS nama_siswa, t.judul AS judul_tugas, mapel.nama_mapel, kelas.nama_kelas, pt.submitted_at')
            ->from($this->pengumpulan_table . ' pt')
            ->join('users u', 'u.id = pt.siswa_id')
            ->join($this->table . ' t', 't.id = pt.tugas_id')
            ->join('mapel', 'mapel.id = t.mapel_id')
            ->join('kelas', 'kelas.id = mapel.kelas_id', 'left')
            ->where('mapel.guru_id', $guru_id)
            ->where_in('pt.status', ['menunggu', 'terlambat'])
            ->order_by('pt.submitted_at', 'DESC')
            ->limit($limit)
            ->get()
            ->result();
    }
}
