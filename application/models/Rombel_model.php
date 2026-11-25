<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rombel_model extends CI_Model {

    protected $table         = 'rombel';
    protected $anggota_table = 'rombel_siswa';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_all($tingkat = NULL)
    {
        $this->db
            ->select('r.*,
                (SELECT COUNT(*) FROM ' . $this->anggota_table . ' rs WHERE rs.rombel_id = r.id) AS jumlah_siswa,
                (SELECT COUNT(*) FROM kelas k WHERE k.rombel_id = r.id) AS jumlah_kelas', FALSE)
            ->from($this->table . ' r');

        if ($tingkat && in_array($tingkat, ['X', 'XI', 'XII'], TRUE)) {
            $this->db->where('r.tingkat', $tingkat);
        }

        $this->db->order_by("FIELD(r.tingkat, 'X', 'XI', 'XII')", 'ASC', FALSE);
        $this->db->order_by('r.nama_rombel', 'ASC');

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
            ->select('r.*,
                (SELECT COUNT(*) FROM ' . $this->anggota_table . ' rs WHERE rs.rombel_id = r.id) AS jumlah_siswa,
                (SELECT COUNT(*) FROM kelas k WHERE k.rombel_id = r.id) AS jumlah_kelas', FALSE)
            ->from($this->table . ' r')
            ->where('r.id', $id)
            ->get()
            ->row();
    }

    public function get_by_kode($kode)
    {
        return $this->db
            ->where('kode_rombel', strtoupper(trim($kode)))
            ->get($this->table)
            ->row();
    }

    public function get_rombel_of_siswa($siswa_id)
    {
        return $this->db
            ->select('r.*, rs.joined_at')
            ->from($this->anggota_table . ' rs')
            ->join($this->table . ' r', 'r.id = rs.rombel_id')
            ->where('rs.siswa_id', $siswa_id)
            ->get()
            ->row();
    }

    public function is_member($rombel_id, $siswa_id)
    {
        return $this->db
            ->where('rombel_id', $rombel_id)
            ->where('siswa_id', $siswa_id)
            ->count_all_results($this->anggota_table) > 0;
    }

    public function get_siswa($rombel_id)
    {
        return $this->db
            ->select('u.id, u.nama, u.email, u.foto, rs.joined_at')
            ->from($this->anggota_table . ' rs')
            ->join('users u', 'u.id = rs.siswa_id')
            ->where('rs.rombel_id', $rombel_id)
            ->order_by('u.nama', 'ASC')
            ->get()
            ->result();
    }

    public function get_siswa_outside_rombel()
    {
        return $this->db
            ->select('u.id, u.nama, u.email')
            ->from('users u')
            ->where('u.role', 'siswa')
            ->where('u.id NOT IN (SELECT rs.siswa_id FROM ' . $this->anggota_table . ' rs)', NULL, FALSE)
            ->order_by('u.nama', 'ASC')
            ->get()
            ->result();
    }

    public function get_kelas_ids($rombel_id)
    {
        $rows = $this->db
            ->select('id')
            ->where('rombel_id', $rombel_id)
            ->get('kelas')
            ->result();

        $ids = array();
        foreach ($rows as $row) {
            $ids[] = (int) $row->id;
        }
        return $ids;
    }

    public function get_kelas($rombel_id)
    {
        return $this->db
            ->select('k.*, u.nama AS nama_guru,
                (SELECT COUNT(*) FROM kelas_siswa ks WHERE ks.kelas_id = k.id) AS jumlah_siswa,
                (SELECT COUNT(*) FROM tugas t WHERE t.kelas_id = k.id) AS jumlah_tugas', FALSE)
            ->from('kelas k')
            ->join('users u', 'u.id = k.guru_id', 'left')
            ->where('k.rombel_id', $rombel_id)
            ->order_by('k.nama_kelas', 'ASC')
            ->get()
            ->result();
    }

    public function count_members($rombel_id)
    {
        return (int) $this->db
            ->where('rombel_id', $rombel_id)
            ->count_all_results($this->anggota_table);
    }

    public function count_kelas($rombel_id)
    {
        return (int) $this->db
            ->where('rombel_id', $rombel_id)
            ->count_all_results('kelas');
    }

    public function insert($data)
    {
        if (empty($data['kode_rombel'])) {
            $data['kode_rombel'] = $this->generate_kode();
        } else {
            $data['kode_rombel'] = strtoupper(trim($data['kode_rombel']));
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
     * Tambahkan siswa ke rombel. Mengembalikan FALSE jika siswa sudah punya rombel.
     */
    public function add_siswa($rombel_id, $siswa_id)
    {
        $existing = $this->db
            ->where('siswa_id', $siswa_id)
            ->count_all_results($this->anggota_table);

        if ($existing > 0) {
            return FALSE;
        }

        $this->db->insert($this->anggota_table, array(
            'rombel_id' => $rombel_id,
            'siswa_id'  => $siswa_id,
            'joined_at' => date('Y-m-d H:i:s'),
        ));

        return $this->db->insert_id();
    }

    public function remove_siswa($rombel_id, $siswa_id)
    {
        $this->db
            ->where('rombel_id', $rombel_id)
            ->where('siswa_id', $siswa_id)
            ->delete($this->anggota_table);

        return $this->db->affected_rows();
    }

    /**
     * Cerminkan keanggotaan rombel ke kelas_siswa (idempoten).
     */
    public function sync_kelas_siswa($rombel_id, $kelas_id = NULL)
    {
        $sql = 'INSERT IGNORE INTO kelas_siswa (kelas_id, siswa_id, joined_at)
                SELECT k.id, rs.siswa_id, ?
                FROM kelas k
                JOIN ' . $this->anggota_table . ' rs ON rs.rombel_id = k.rombel_id
                WHERE k.rombel_id = ?';
        $params = array(date('Y-m-d H:i:s'), $rombel_id);

        if ($kelas_id) {
            $sql .= ' AND k.id = ?';
            $params[] = (int) $kelas_id;
        }

        $this->db->query($sql, $params);
    }

    public function remove_kelas_siswa($rombel_id, $siswa_id)
    {
        $this->db
            ->where('siswa_id', $siswa_id)
            ->where('kelas_id IN (SELECT id FROM kelas WHERE rombel_id = ' . (int) $rombel_id . ')', NULL, FALSE)
            ->delete('kelas_siswa');

        return $this->db->affected_rows();
    }

    /**
     * Apakah siswa sudah memiliki pengumpulan (individu atau kelompok)
     * pada tugas di kelas-kelas rombel ini.
     */
    public function has_submissions($rombel_id, $siswa_id)
    {
        $sql = 'SELECT COUNT(*) AS total
                FROM pengumpulan_tugas p
                JOIN tugas t ON t.id = p.tugas_id
                JOIN kelas k ON k.id = t.kelas_id
                WHERE k.rombel_id = ?
                  AND (
                    p.siswa_id = ?
                    OR p.kelompok_id IN (
                      SELECT ka.kelompok_id FROM kelompok_anggota ka WHERE ka.siswa_id = ?
                    )
                  )';

        $row = $this->db->query($sql, array($rombel_id, $siswa_id, $siswa_id))->row();
        return $row && (int) $row->total > 0;
    }

    /**
     * Lepaskan siswa dari kelompok pada tugas di rombel ini yang belum
     * memiliki pengumpulan. Tugas yang sudah dikumpulkan dibiarkan utuh.
     */
    public function remove_from_groups($rombel_id, $siswa_id)
    {
        $sql = 'SELECT ka.id
                FROM kelompok_anggota ka
                JOIN kelompok g ON g.id = ka.kelompok_id
                JOIN tugas t ON t.id = g.tugas_id
                JOIN kelas k ON k.id = t.kelas_id
                WHERE ka.siswa_id = ?
                  AND k.rombel_id = ?
                  AND NOT EXISTS (
                    SELECT 1 FROM pengumpulan_tugas p WHERE p.kelompok_id = g.id
                  )';

        $rows = $this->db->query($sql, array($siswa_id, $rombel_id))->result();

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
                ->where('kode_rombel', $kode)
                ->count_all_results($this->table);
        } while ($count > 0);

        return $kode;
    }
}
