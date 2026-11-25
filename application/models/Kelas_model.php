<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Kelas_model — kelas XI/XII (rombongan belajar).
 * Keanggotaan siswa disimpan pada tabel kelas_siswa (satu siswa satu kelas).
 */
class Kelas_model extends CI_Model {

    protected $table         = 'kelas';
    protected $anggota_table = 'kelas_siswa';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_all($tingkat = NULL)
    {
        $this->db
            ->select('k.*,
                (SELECT COUNT(*) FROM ' . $this->anggota_table . ' ks WHERE ks.kelas_id = k.id) AS jumlah_siswa,
                (SELECT COUNT(*) FROM mapel m WHERE m.kelas_id = k.id) AS jumlah_mapel', FALSE)
            ->from($this->table . ' k');

        if ($tingkat && in_array($tingkat, ['X', 'XI', 'XII'], TRUE)) {
            $this->db->where('k.tingkat', $tingkat);
        }

        $this->db->order_by("FIELD(k.tingkat, 'X', 'XI', 'XII')", 'ASC', FALSE);
        $this->db->order_by('k.nama_kelas', 'ASC');

        return $this->db->get()->result();
    }

    public function get_by_id($id)
    {
        return $this->db
            ->where('id', $id)
            ->get($this->table)
            ->row();
    }

    public function get_with_counts($id)
    {
        return $this->db
            ->select('k.*,
                (SELECT COUNT(*) FROM ' . $this->anggota_table . ' ks WHERE ks.kelas_id = k.id) AS jumlah_siswa,
                (SELECT COUNT(*) FROM mapel m WHERE m.kelas_id = k.id) AS jumlah_mapel', FALSE)
            ->from($this->table . ' k')
            ->where('k.id', $id)
            ->get()
            ->row();
    }

    public function get_by_kode($kode)
    {
        return $this->db
            ->where('kode_kelas', strtoupper(trim($kode)))
            ->get($this->table)
            ->row();
    }

    public function get_kelas_of_siswa($siswa_id)
    {
        return $this->db
            ->select('k.*, ks.joined_at')
            ->from($this->anggota_table . ' ks')
            ->join($this->table . ' k', 'k.id = ks.kelas_id')
            ->where('ks.siswa_id', $siswa_id)
            ->get()
            ->row();
    }

    public function is_member($kelas_id, $siswa_id)
    {
        return $this->db
            ->where('kelas_id', $kelas_id)
            ->where('siswa_id', $siswa_id)
            ->count_all_results($this->anggota_table) > 0;
    }

    public function get_siswa($kelas_id)
    {
        return $this->db
            ->select('u.id, u.nama, u.email, u.foto, ks.joined_at')
            ->from($this->anggota_table . ' ks')
            ->join('users u', 'u.id = ks.siswa_id')
            ->where('ks.kelas_id', $kelas_id)
            ->order_by('u.nama', 'ASC')
            ->get()
            ->result();
    }

    public function get_siswa_outside_kelas($tingkat = NULL, $jurusan = NULL, $search = NULL)
    {
        $this->db
            ->select('u.id, u.nama, u.email, u.tingkat, u.jurusan')
            ->from('users u')
            ->where('u.role', 'siswa')
            ->where('u.id NOT IN (SELECT ks.siswa_id FROM ' . $this->anggota_table . ' ks)', NULL, FALSE);

        if ($tingkat) {
            $this->db->where('u.tingkat', $tingkat);
        }
        if ($jurusan) {
            $this->db->like('u.jurusan', $jurusan);
        }
        if ($search) {
            $this->db->group_start()
                ->like('u.nama', $search)
                ->or_like('u.email', $search)
                ->group_end();
        }

        $this->db->order_by('u.nama', 'ASC');
        return $this->db->get()->result();
    }

    public function get_mapel_ids($kelas_id)
    {
        $rows = $this->db
            ->select('id')
            ->where('kelas_id', $kelas_id)
            ->get('mapel')
            ->result();

        $ids = array();
        foreach ($rows as $row) {
            $ids[] = (int) $row->id;
        }
        return $ids;
    }

    public function get_mapel($kelas_id)
    {
        return $this->db
            ->select('m.*, u.nama AS nama_guru,
                (SELECT COUNT(*) FROM mapel_siswa ms WHERE ms.mapel_id = m.id) AS jumlah_siswa,
                (SELECT COUNT(*) FROM tugas t WHERE t.mapel_id = m.id) AS jumlah_tugas', FALSE)
            ->from('mapel m')
            ->join('users u', 'u.id = m.guru_id', 'left')
            ->where('m.kelas_id', $kelas_id)
            ->order_by('m.nama_mapel', 'ASC')
            ->get()
            ->result();
    }

    public function count_members($kelas_id)
    {
        return (int) $this->db
            ->where('kelas_id', $kelas_id)
            ->count_all_results($this->anggota_table);
    }

    public function count_mapel($kelas_id)
    {
        return (int) $this->db
            ->where('kelas_id', $kelas_id)
            ->count_all_results('mapel');
    }

    public function insert($data)
    {
        if (empty($data['kode_kelas'])) {
            $data['kode_kelas'] = $this->generate_kode();
        } else {
            $data['kode_kelas'] = strtoupper(trim($data['kode_kelas']));
        }

        if (isset($data['jurusan'])) {
            $data['jurusan'] = trim($data['jurusan']) !== '' ? strtoupper(trim($data['jurusan'])) : NULL;
        }

        $data['created_at'] = date('Y-m-d H:i:s');
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function update($id, $data)
    {
        if (isset($data['jurusan'])) {
            $data['jurusan'] = trim($data['jurusan']) !== '' ? strtoupper(trim($data['jurusan'])) : NULL;
        }

        $this->db->where('id', $id)->update($this->table, $data);
        return $this->db->affected_rows();
    }

    public function delete($id)
    {
        $this->db->where('id', $id)->delete($this->table);
        return $this->db->affected_rows();
    }

    /**
     * Tambahkan siswa ke kelas. FALSE jika siswa sudah punya kelas.
     */
    public function add_siswa($kelas_id, $siswa_id)
    {
        $existing = $this->db
            ->where('siswa_id', $siswa_id)
            ->count_all_results($this->anggota_table);

        if ($existing > 0) {
            return FALSE;
        }

        $this->db->insert($this->anggota_table, array(
            'kelas_id'  => $kelas_id,
            'siswa_id'  => $siswa_id,
            'joined_at' => date('Y-m-d H:i:s'),
        ));

        return $this->db->insert_id();
    }

    /**
     * Tambahkan daftar siswa ke kelas secara massal.
     */
    public function add_siswa_batch($kelas_id, $siswa_ids)
    {
        $this->db->trans_start();
        foreach ($siswa_ids as $siswa_id) {
            $this->add_siswa($kelas_id, $siswa_id);
        }
        $this->sync_mapel_siswa($kelas_id);
        $this->db->trans_complete();
        return $this->db->trans_status();
    }


    public function remove_siswa($kelas_id, $siswa_id)
    {
        $this->db
            ->where('kelas_id', $kelas_id)
            ->where('siswa_id', $siswa_id)
            ->delete($this->anggota_table);

        return $this->db->affected_rows();
    }

    /**
     * Cerminkan keanggotaan kelas ke mapel_siswa (idempoten).
     */
    public function sync_mapel_siswa($kelas_id, $mapel_id = NULL)
    {
        $sql = 'INSERT IGNORE INTO mapel_siswa (mapel_id, siswa_id, joined_at)
                SELECT m.id, ks.siswa_id, ?
                FROM mapel m
                JOIN ' . $this->anggota_table . ' ks ON ks.kelas_id = m.kelas_id
                WHERE m.kelas_id = ?';
        $params = array(date('Y-m-d H:i:s'), $kelas_id);

        if ($mapel_id) {
            $sql .= ' AND m.id = ?';
            $params[] = (int) $mapel_id;
        }

        $this->db->query($sql, $params);
    }

    public function remove_mapel_siswa($kelas_id, $siswa_id)
    {
        $this->db
            ->where('siswa_id', $siswa_id)
            ->where('mapel_id IN (SELECT id FROM mapel WHERE kelas_id = ' . (int) $kelas_id . ')', NULL, FALSE)
            ->delete('mapel_siswa');

        return $this->db->affected_rows();
    }

    /**
     * Apakah siswa sudah memiliki pengumpulan (individu atau kelompok)
     * pada tugas di mapel-mapel kelas ini.
     */
    public function has_submissions($kelas_id, $siswa_id)
    {
        $sql = 'SELECT COUNT(*) AS total
                FROM pengumpulan_tugas p
                JOIN tugas t ON t.id = p.tugas_id
                JOIN mapel m ON m.id = t.mapel_id
                WHERE m.kelas_id = ?
                  AND (
                    p.siswa_id = ?
                    OR p.kelompok_id IN (
                      SELECT ka.kelompok_id FROM kelompok_anggota ka WHERE ka.siswa_id = ?
                    )
                  )';

        $row = $this->db->query($sql, array($kelas_id, $siswa_id, $siswa_id))->row();
        return $row && (int) $row->total > 0;
    }

    /**
     * Lepaskan siswa dari kelompok pada tugas di kelas ini yang belum
     * memiliki pengumpulan. Tugas yang sudah dikumpulkan dibiarkan utuh.
     */
    public function remove_from_groups($kelas_id, $siswa_id)
    {
        $sql = 'SELECT ka.id
                FROM kelompok_anggota ka
                JOIN kelompok g ON g.id = ka.kelompok_id
                JOIN tugas t ON t.id = g.tugas_id
                JOIN mapel m ON m.id = t.mapel_id
                WHERE ka.siswa_id = ?
                  AND m.kelas_id = ?
                  AND NOT EXISTS (
                    SELECT 1 FROM pengumpulan_tugas p WHERE p.kelompok_id = g.id
                  )';

        $rows = $this->db->query($sql, array($siswa_id, $kelas_id))->result();

        if (empty($rows)) {
            return 0;
        }

        $ids = array();
        foreach ($rows as $row) {
            $ids[] = (int) $row->id;
        }

        $this->db->where_in('id', $ids)->delete('kelompok_anggota');
        return $this->db->affected_rows();
    }

    public function generate_kode()
    {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        do {
            $kode = '';
            for ($i = 0; $i < 6; $i++) {
                $kode .= $chars[random_int(0, strlen($chars) - 1)];
            }
            $count = $this->db
                ->where('kode_kelas', $kode)
                ->count_all_results($this->table);
        } while ($count > 0);

        return $kode;
    }
}
