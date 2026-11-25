<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Siswa_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     * Kelas XI/XII tempat siswa terdaftar (satu siswa satu kelas).
     */
    public function get_kelas_by_siswa($siswa_id) {
        $this->db->select('k.*, ks.joined_at,
            (SELECT COUNT(ks2.id) FROM kelas_siswa ks2 WHERE ks2.kelas_id = k.id) as total_siswa');
        $this->db->from('kelas_siswa ks');
        $this->db->join('kelas k', 'k.id = ks.kelas_id');
        $this->db->where('ks.siswa_id', $siswa_id);
        return $this->db->get()->row();
    }

    /**
     * Daftar mapel yang diikuti siswa (hasil mirror kelas_siswa).
     */
    public function get_mapel_by_siswa($siswa_id) {
        $this->db->select('m.*, u.nama as nama_guru,
            k.nama_kelas, k.tingkat, k.jurusan, k.kode_kelas,
            (SELECT COUNT(ms2.id) FROM mapel_siswa ms2 WHERE ms2.mapel_id = m.id) as total_siswa');
        $this->db->from('mapel_siswa ms');
        $this->db->join('mapel m', 'm.id = ms.mapel_id');
        $this->db->join('kelas k', 'k.id = m.kelas_id', 'left');
        $this->db->join('users u', 'u.id = m.guru_id', 'left');
        $this->db->where('ms.siswa_id', $siswa_id);
        $this->db->order_by('m.nama_mapel', 'ASC');
        return $this->db->get()->result();
    }

    public function is_enrolled_mapel($siswa_id, $mapel_id) {
        $query = $this->db->get_where('mapel_siswa', array('siswa_id' => $siswa_id, 'mapel_id' => $mapel_id));
        return $query->num_rows() > 0;
    }

    public function get_mapel_detail($id) {
        $this->db->select('m.*, u.nama as nama_guru, u.email as email_guru,
            k.nama_kelas, k.tingkat, k.jurusan, k.kode_kelas');
        $this->db->from('mapel m');
        $this->db->join('users u', 'u.id = m.guru_id', 'left');
        $this->db->join('kelas k', 'k.id = m.kelas_id', 'left');
        $this->db->where('m.id', $id);
        return $this->db->get()->row();
    }

    public function get_materi_by_mapel($mapel_id) {
        $this->db->where('mapel_id', $mapel_id);
        $this->db->order_by('urutan', 'ASC');
        $this->db->order_by('id', 'ASC');
        return $this->db->get('materi')->result();
    }

    public function get_materi($id) {
        $this->db->select('m.*, mp.nama_mapel, k.nama_kelas');
        $this->db->from('materi m');
        $this->db->join('mapel mp', 'mp.id = m.mapel_id');
        $this->db->join('kelas k', 'k.id = mp.kelas_id', 'left');
        $this->db->where('m.id', $id);
        return $this->db->get()->row();
    }

    /**
     * Batasi tugas yang tampil: tugas tanpa penerima = untuk semua siswa,
     * tugas dengan penerima = hanya untuk siswa terpilih.
     * Wajib menggunakan alias tabel tugas = "t".
     */
    private function _where_penerima_for_siswa($siswa_id) {
        $sid = (int) $siswa_id;

        $this->db->group_start();
        $this->db->where('NOT EXISTS (SELECT 1 FROM tugas_penerima tp0 WHERE tp0.tugas_id = t.id)', NULL, FALSE);
        $this->db->or_where('EXISTS (SELECT 1 FROM tugas_penerima tp1 WHERE tp1.tugas_id = t.id AND tp1.siswa_id = ' . $sid . ')', NULL, FALSE);
        $this->db->group_end();
    }

    public function is_penerima_tugas($tugas_id, $siswa_id) {
        $total = $this->db->where('tugas_id', $tugas_id)->count_all_results('tugas_penerima');
        if ($total === 0) {
            return TRUE;
        }

        return $this->db
            ->where('tugas_id', $tugas_id)
            ->where('siswa_id', $siswa_id)
            ->count_all_results('tugas_penerima') > 0;
    }

    private function _join_pengumpulan_for_siswa($siswa_id) {
        $sid = (int) $siswa_id;

        $this->db->join('pengumpulan_tugas p',
            'p.id = (
                SELECT p2.id FROM pengumpulan_tugas p2
                WHERE p2.tugas_id = t.id
                  AND (
                    p2.siswa_id = ' . $sid . '
                    OR p2.kelompok_id IN (
                      SELECT ka.kelompok_id FROM kelompok_anggota ka
                      JOIN kelompok g ON g.id = ka.kelompok_id
                      WHERE ka.siswa_id = ' . $sid . ' AND g.tugas_id = t.id
                    )
                  )
                ORDER BY p2.id DESC LIMIT 1
            )', 'left', FALSE);

        $this->db->select(
            '(SELECT g2.nama_kelompok FROM kelompok_anggota ka2
              JOIN kelompok g2 ON g2.id = ka2.kelompok_id
              WHERE ka2.siswa_id = ' . $sid . ' AND g2.tugas_id = t.id
              LIMIT 1) AS nama_kelompok', FALSE);
    }

    /**
     * Batasi tugas yang boleh dilihat siswa:
     * - published selalu tampil
     * - scheduled tampil setelah publish_at terlewati
     * - draft tidak pernah tampil
     * Wajib menggunakan alias tabel tugas = "t".
     */
    private function _where_published_for_siswa() {
        $now = date('Y-m-d H:i:s');

        $this->db->group_start();
        $this->db->where('t.status', 'published');
        $this->db->or_group_start();
        $this->db->where('t.status', 'scheduled');
        $this->db->where('t.publish_at IS NOT NULL', NULL, FALSE);
        $this->db->where('t.publish_at <=', $now);
        $this->db->group_end();
        $this->db->group_end();
    }

    public function get_tugas_by_mapel_with_status($mapel_id, $siswa_id) {
        $this->db->select('t.*, p.id as pengumpulan_id, p.status as status_pengumpulan, p.nilai, p.submitted_at');
        $this->db->from('tugas t');
        $this->_join_pengumpulan_for_siswa($siswa_id);
        $this->db->where('t.mapel_id', $mapel_id);
        $this->_where_penerima_for_siswa($siswa_id);
        $this->_where_published_for_siswa();
        $this->db->order_by('t.deadline', 'ASC');
        return $this->db->get()->result();
    }

    public function get_upcoming_tugas($siswa_id, $limit = 5) {
        $this->db->select('t.*, mp.nama_mapel, k.nama_kelas, p.id as pengumpulan_id, p.status as status_pengumpulan, p.nilai, p.submitted_at');
        $this->db->from('mapel_siswa ms');
        $this->db->join('tugas t', 't.mapel_id = ms.mapel_id');
        $this->db->join('mapel mp', 'mp.id = t.mapel_id');
        $this->db->join('kelas k', 'k.id = mp.kelas_id', 'left');
        $this->_join_pengumpulan_for_siswa($siswa_id);
        $this->db->where('ms.siswa_id', $siswa_id);
        $this->_where_penerima_for_siswa($siswa_id);
        $this->_where_published_for_siswa();
        $this->db->order_by('t.deadline', 'ASC');
        if ($limit) {
            $this->db->limit($limit);
        }
        return $this->db->get()->result();
    }

    public function get_all_tugas_for_siswa($siswa_id, $filter = 'semua') {
        $this->db->select('t.*, mp.nama_mapel, k.nama_kelas, p.id as pengumpulan_id, p.status as status_pengumpulan, p.nilai, p.submitted_at');
        $this->db->from('mapel_siswa ms');
        $this->db->join('tugas t', 't.mapel_id = ms.mapel_id');
        $this->db->join('mapel mp', 'mp.id = t.mapel_id');
        $this->db->join('kelas k', 'k.id = mp.kelas_id', 'left');
        $this->_join_pengumpulan_for_siswa($siswa_id);
        $this->db->where('ms.siswa_id', $siswa_id);
        $this->_where_penerima_for_siswa($siswa_id);
        $this->_where_published_for_siswa();

        $now = date('Y-m-d H:i:s');
        if ($filter === 'belum_dikumpulkan') {
            $this->db->where('p.id IS NULL', null, false);
            $this->db->where('t.deadline >=', $now);
        } elseif ($filter === 'menunggu') {
            $this->db->where('p.status', 'menunggu');
        } elseif ($filter === 'revisi') {
            $this->db->where('p.status', 'revisi');
        } elseif ($filter === 'dinilai') {
            $this->db->where('p.status', 'dinilai');
        } elseif ($filter === 'terlambat') {
            $this->db->group_start();
            $this->db->where('p.status', 'terlambat');
            $this->db->or_group_start();
            $this->db->where('p.id IS NULL', null, false);
            $this->db->where('t.deadline <', $now);
            $this->db->group_end();
            $this->db->group_end();
        }

        $this->db->order_by('t.deadline', 'ASC');
        return $this->db->get()->result();
    }

    public function get_recent_pengumpulan($siswa_id, $limit = 5) {
        $this->db->select('p.*, t.judul as judul_tugas, mp.nama_mapel, k.nama_kelas');
        $this->db->from('pengumpulan_tugas p');
        $this->db->join('tugas t', 't.id = p.tugas_id');
        $this->db->join('mapel mp', 'mp.id = t.mapel_id');
        $this->db->join('kelas k', 'k.id = mp.kelas_id', 'left');
        $this->db->where('p.siswa_id', $siswa_id);
        $this->db->order_by('p.submitted_at', 'DESC');
        $this->db->limit($limit);
        return $this->db->get()->result();
    }

    public function get_materi_terbaru($siswa_id, $limit = 3) {
        $this->db->select('m.*, mp.nama_mapel, k.nama_kelas');
        $this->db->from('mapel_siswa ms');
        $this->db->join('materi m', 'm.mapel_id = ms.mapel_id');
        $this->db->join('mapel mp', 'mp.id = m.mapel_id');
        $this->db->join('kelas k', 'k.id = mp.kelas_id', 'left');
        $this->db->where('ms.siswa_id', $siswa_id);
        $this->db->order_by('m.created_at', 'DESC');
        $this->db->limit($limit);
        return $this->db->get()->result();
    }

    public function count_tugas_aktif($siswa_id) {
        $now = date('Y-m-d H:i:s');
        $this->db->from('mapel_siswa ms');
        $this->db->join('tugas t', 't.mapel_id = ms.mapel_id');
        $this->_join_pengumpulan_for_siswa($siswa_id);
        $this->db->where('ms.siswa_id', $siswa_id);
        $this->_where_penerima_for_siswa($siswa_id);
        $this->_where_published_for_siswa();
        $this->db->where('p.id IS NULL', null, false);
        $this->db->where('t.deadline >=', $now);
        return $this->db->count_all_results();
    }

    public function count_tugas_selesai($siswa_id) {
        $sid = (int) $siswa_id;
        $sql = 'SELECT COUNT(DISTINCT p.tugas_id) AS total
                FROM pengumpulan_tugas p
                WHERE p.siswa_id = ?
                   OR p.kelompok_id IN (
                     SELECT ka.kelompok_id FROM kelompok_anggota ka WHERE ka.siswa_id = ?
                   )';
        $row = $this->db->query($sql, array($sid, $sid))->row();
        return $row ? (int) $row->total : 0;
    }

    public function get_tugas_detail($id) {
        $this->db->select('t.*, mp.nama_mapel, k.nama_kelas, k.tingkat, k.jurusan, u.nama as nama_guru');
        $this->db->from('tugas t');
        $this->db->join('mapel mp', 'mp.id = t.mapel_id');
        $this->db->join('kelas k', 'k.id = mp.kelas_id', 'left');
        $this->db->join('users u', 'u.id = mp.guru_id', 'left');
        $this->db->where('t.id', $id);
        $this->_where_published_for_siswa();
        return $this->db->get()->row();
    }

    public function get_pengumpulan($tugas_id, $siswa_id) {
        $this->db->where('tugas_id', $tugas_id);
        $this->db->where('siswa_id', $siswa_id);
        return $this->db->get('pengumpulan_tugas')->row();
    }

    public function insert_pengumpulan($data) {
        $this->db->insert('pengumpulan_tugas', $data);
        return $this->db->insert_id();
    }

    public function update_pengumpulan($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('pengumpulan_tugas', $data);
    }

    public function get_user($id) {
        return $this->db->get_where('users', array('id' => $id))->row();
    }

    public function update_user($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('users', $data);
    }

    public function get_notifikasi($user_id) {
        $this->db->where('user_id', $user_id);
        $this->db->order_by('created_at', 'DESC');
        return $this->db->get('notifikasi')->result();
    }

    public function mark_notifikasi_read($user_id) {
        $this->db->where('user_id', $user_id);
        return $this->db->update('notifikasi', array('is_read' => 1));
    }
}
