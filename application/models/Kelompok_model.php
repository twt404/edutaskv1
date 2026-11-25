<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kelompok_model extends CI_Model {

    protected $table         = 'kelompok';
    protected $anggota_table = 'kelompok_anggota';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_by_id($id)
    {
        return $this->db
            ->where('id', $id)
            ->get($this->table)
            ->row();
    }

    public function get_by_tugas($tugas_id)
    {
        return $this->db
            ->select('k.*, COUNT(ka.id) AS jumlah_anggota')
            ->from($this->table . ' k')
            ->join($this->anggota_table . ' ka', 'ka.kelompok_id = k.id', 'left')
            ->where('k.tugas_id', $tugas_id)
            ->group_by('k.id')
            ->order_by('k.id', 'ASC')
            ->get()
            ->result();
    }

    public function get_with_members($tugas_id)
    {
        $groups = $this->db
            ->where('tugas_id', $tugas_id)
            ->order_by('id', 'ASC')
            ->get($this->table)
            ->result();

        if (empty($groups)) {
            return array();
        }

        $group_ids = array();
        foreach ($groups as $g) {
            $group_ids[] = (int) $g->id;
        }

        $members = $this->db
            ->select('ka.kelompok_id, u.id, u.nama, u.email, u.foto')
            ->from($this->anggota_table . ' ka')
            ->join('users u', 'u.id = ka.siswa_id')
            ->where_in('ka.kelompok_id', $group_ids)
            ->order_by('u.nama', 'ASC')
            ->get()
            ->result();

        $map = array();
        foreach ($members as $m) {
            $map[(int) $m->kelompok_id][] = $m;
        }

        foreach ($groups as $g) {
            $g->members = isset($map[(int) $g->id]) ? $map[(int) $g->id] : array();
        }

        return $groups;
    }

    public function get_group_of_siswa($tugas_id, $siswa_id)
    {
        return $this->db
            ->select('k.*, ka.id AS anggota_id')
            ->from($this->anggota_table . ' ka')
            ->join($this->table . ' k', 'k.id = ka.kelompok_id')
            ->where('k.tugas_id', $tugas_id)
            ->where('ka.siswa_id', $siswa_id)
            ->get()
            ->row();
    }

    public function is_in_any_group($tugas_id, $siswa_id)
    {
        return $this->db
            ->from($this->anggota_table . ' ka')
            ->join($this->table . ' k', 'k.id = ka.kelompok_id')
            ->where('k.tugas_id', $tugas_id)
            ->where('ka.siswa_id', $siswa_id)
            ->count_all_results() > 0;
    }

    public function count_anggota($kelompok_id)
    {
        return (int) $this->db
            ->where('kelompok_id', $kelompok_id)
            ->count_all_results($this->anggota_table);
    }

    public function create_groups($tugas_id, $jumlah)
    {
        $ids = array();
        for ($i = 1; $i <= $jumlah; $i++) {
            $this->db->insert($this->table, array(
                'tugas_id'      => $tugas_id,
                'nama_kelompok' => 'Kelompok ' . $i,
                'created_at'    => date('Y-m-d H:i:s'),
            ));
            $ids[] = $this->db->insert_id();
        }
        return $ids;
    }

    public function add_group($tugas_id, $nama_kelompok)
    {
        $this->db->insert($this->table, array(
            'tugas_id'      => $tugas_id,
            'nama_kelompok' => $nama_kelompok,
            'created_at'    => date('Y-m-d H:i:s'),
        ));
        return $this->db->insert_id();
    }

    public function delete_group($kelompok_id)
    {
        $this->db->where('id', $kelompok_id)->delete($this->table);
        return $this->db->affected_rows();
    }

    public function delete_by_tugas($tugas_id)
    {
        $this->db->where('tugas_id', $tugas_id)->delete($this->table);
        return $this->db->affected_rows();
    }

    public function add_anggota($kelompok_id, $siswa_id)
    {
        $exists = $this->db
            ->where('kelompok_id', $kelompok_id)
            ->where('siswa_id', $siswa_id)
            ->count_all_results($this->anggota_table);

        if ($exists > 0) {
            return FALSE;
        }

        $this->db->insert($this->anggota_table, array(
            'kelompok_id' => $kelompok_id,
            'siswa_id'    => $siswa_id,
        ));
        return $this->db->insert_id();
    }

    public function remove_anggota($kelompok_id, $siswa_id)
    {
        $this->db
            ->where('kelompok_id', $kelompok_id)
            ->where('siswa_id', $siswa_id)
            ->delete($this->anggota_table);
        return $this->db->affected_rows();
    }

    public function remove_anggota_by_tugas($tugas_id, $siswa_id)
    {
        $this->db
            ->where('siswa_id', $siswa_id)
            ->where('kelompok_id IN (SELECT id FROM kelompok WHERE tugas_id = ' . (int) $tugas_id . ')', NULL, FALSE)
            ->delete($this->anggota_table);
        return $this->db->affected_rows();
    }

    public function get_unassigned_students($tugas_id, $mapel_id)
    {
        $sql = 'SELECT u.id, u.nama, u.email, u.foto
                FROM mapel_siswa ms
                JOIN users u ON u.id = ms.siswa_id
                WHERE ms.mapel_id = ?
                  AND u.id NOT IN (
                    SELECT ka.siswa_id
                    FROM kelompok_anggota ka
                    JOIN kelompok k ON k.id = ka.kelompok_id
                    WHERE k.tugas_id = ?
                  )
                ORDER BY u.nama ASC';

        return $this->db->query($sql, array($mapel_id, $tugas_id))->result();
    }

    /**
     * Buat ulang kelompok dan bagi seluruh siswa mapel secara acak dan merata.
     * PENTING: pemanggil wajib memastikan belum ada pengumpulan kelompok.
     */
    public function assign_random($tugas_id, $mapel_id, $jumlah_kelompok, $anggota_per_kelompok)
    {
        $jumlah_kelompok = max(1, (int) $jumlah_kelompok);

        $students = $this->db
            ->select('siswa_id')
            ->where('mapel_id', $mapel_id)
            ->order_by('id', 'ASC')
            ->get('mapel_siswa')
            ->result();

        $student_ids = array();
        foreach ($students as $s) {
            $student_ids[] = (int) $s->siswa_id;
        }

        if ( ! empty($student_ids)) {
            shuffle($student_ids);
        }

        $this->delete_by_tugas($tugas_id);
        $group_ids = $this->create_groups($tugas_id, $jumlah_kelompok);

        foreach ($student_ids as $index => $siswa_id) {
            $group_id = $group_ids[$index % $jumlah_kelompok];
            $this->add_anggota($group_id, $siswa_id);
        }

        return $group_ids;
    }

    /**
     * Masukkan siswa ke kelompok acak pada semua tugas acak di satu mapel.
     */
    public function auto_assign_for_mapel($mapel_id, $siswa_id)
    {
        $tasks = $this->db
            ->select('id')
            ->where('mapel_id', $mapel_id)
            ->where('jenis', 'kelompok')
            ->where('mode_kelompok', 'acak')
            ->where('kelompok_locked', 0)
            ->get('tugas')
            ->result();

        foreach ($tasks as $t) {
            if ( ! $this->is_in_any_group($t->id, $siswa_id)) {
                $this->assign_late_student($t->id, $siswa_id);
            }
        }
    }

    /**
     * Masukkan siswa ke kelompok acak pada semua tugas acak di kelas.
     */
    public function auto_assign_for_kelas($kelas_id, $siswa_id)
    {
        $mapel = $this->db
            ->select('id')
            ->where('kelas_id', $kelas_id)
            ->get('mapel')
            ->result();

        foreach ($mapel as $m) {
            $this->auto_assign_for_mapel($m->id, $siswa_id);
        }
    }

    /**
     * Masukkan siswa yang bergabung ke kelas setelah kelompok acak dibentuk.
     */
    public function assign_late_student($tugas_id, $siswa_id)
    {
        $group = $this->db
            ->select('k.id, COUNT(ka.id) AS jumlah_anggota')
            ->from($this->table . ' k')
            ->join($this->anggota_table . ' ka', 'ka.kelompok_id = k.id', 'left')
            ->where('k.tugas_id', $tugas_id)
            ->group_by('k.id')
            ->order_by('jumlah_anggota', 'ASC')
            ->order_by('k.id', 'ASC')
            ->limit(1)
            ->get()
            ->row();

        if ( ! $group) {
            return FALSE;
        }

        return $this->add_anggota($group->id, $siswa_id);
    }
}
