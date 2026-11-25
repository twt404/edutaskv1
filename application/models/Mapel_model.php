<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Mapel_model — mata pelajaran di dalam kelas.
 * Enrollment siswa disimpan pada tabel mapel_siswa dan dicerminkan
 * otomatis dari keanggotaan kelas (kelas_siswa).
 */
class Mapel_model extends CI_Model {

    protected $table = 'mapel';
    protected $pivot = 'mapel_siswa';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_by_guru($guru_id)
    {
        return $this->db
            ->select('m.*, k.nama_kelas, k.tingkat, k.jurusan, k.kode_kelas,
                (SELECT COUNT(*) FROM ' . $this->pivot . ' ms WHERE ms.mapel_id = m.id) AS jumlah_siswa', FALSE)
            ->from($this->table . ' m')
            ->join('kelas k', 'k.id = m.kelas_id', 'left')
            ->where('m.guru_id', $guru_id)
            ->order_by('k.nama_kelas', 'ASC')
            ->order_by('m.nama_mapel', 'ASC')
            ->get()
            ->result();
    }

    public function get_by_id($id)
    {
        return $this->db
            ->select('m.*, k.nama_kelas, k.tingkat, k.jurusan, k.kode_kelas')
            ->from($this->table . ' m')
            ->join('kelas k', 'k.id = m.kelas_id', 'left')
            ->where('m.id', $id)
            ->get()
            ->row();
    }

    public function get_with_siswa_count($id)
    {
        return $this->db
            ->select('m.*, k.nama_kelas, k.tingkat, k.jurusan, k.kode_kelas,
                (SELECT COUNT(*) FROM ' . $this->pivot . ' ms WHERE ms.mapel_id = m.id) AS jumlah_siswa', FALSE)
            ->from($this->table . ' m')
            ->join('kelas k', 'k.id = m.kelas_id', 'left')
            ->where('m.id', $id)
            ->get()
            ->row();
    }

    public function get_by_kelas($kelas_id)
    {
        return $this->db
            ->where('kelas_id', $kelas_id)
            ->order_by('nama_mapel', 'ASC')
            ->get($this->table)
            ->result();
    }

    public function has_tugas($mapel_id)
    {
        return $this->db
            ->where('mapel_id', $mapel_id)
            ->count_all_results('tugas') > 0;
    }

    public function insert($data)
    {
        if (empty($data['kode_mapel'])) {
            $data['kode_mapel'] = $this->generate_kode();
        } else {
            $data['kode_mapel'] = strtoupper(trim($data['kode_mapel']));
        }

        $data['created_at'] = date('Y-m-d H:i:s');
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function update($id, $data)
    {
        $this->db->where('id', $id)->update($this->table, $data);
        return $this->db->affected_rows();
    }

    public function delete($id)
    {
        // 1. Ambil semua ID tugas di mapel ini
        $tugas_ids = $this->db->select('id')->where('mapel_id', $id)->get('tugas')->result_array();
        $ids = array_column($tugas_ids, 'id');

        $this->db->trans_start();

        if (!empty($ids)) {
            // 2. Hapus notifikasi, berkas item, pengumpulan tugas & kelompok terkait tugas-tugas tersebut
            $this->db->where_in('tugas_id', $ids)->delete('notifikasi');
            $this->db->where('pengumpulan_id IN (SELECT id FROM pengumpulan_tugas WHERE tugas_id IN (' . implode(',', array_map('intval', $ids)) . '))', NULL, FALSE)
                ->delete('pengumpulan_tugas_file');
            $this->db->where_in('tugas_id', $ids)->delete('pengumpulan_tugas');
            $this->db->where_in('tugas_id', $ids)->delete('tugas_item');
            $this->db->where_in('tugas_id', $ids)->delete('kelompok');
            // 3. Hapus tugas itu sendiri
            $this->db->where('mapel_id', $id)->delete('tugas');
        }

        // 4. Hapus materi & enrollment siswa terkait mapel ini
        $this->db->where('mapel_id', $id)->delete('materi');
        $this->db->where('mapel_id', $id)->delete('mapel_siswa');

        // 5. Akhirnya hapus record mapel utama
        $this->db->where('id', $id)->delete($this->table);

        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    public function get_siswa($mapel_id)
    {
        return $this->db
            ->select('u.id, u.nama, u.email, ms.joined_at')
            ->from($this->pivot . ' ms')
            ->join('users u', 'u.id = ms.siswa_id')
            ->where('ms.mapel_id', $mapel_id)
            ->order_by('u.nama', 'ASC')
            ->get()
            ->result();
    }

    public function add_siswa($mapel_id, $siswa_id)
    {
        return $this->db->insert($this->pivot, array(
            'mapel_id'  => $mapel_id,
            'siswa_id'  => $siswa_id,
            'joined_at' => date('Y-m-d H:i:s'),
        ));
    }

    public function remove_siswa($mapel_id, $siswa_id)
    {
        $this->db
            ->where('mapel_id', $mapel_id)
            ->where('siswa_id', $siswa_id)
            ->delete($this->pivot);

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
                ->where('kode_mapel', $kode)
                ->count_all_results($this->table);
        } while ($count > 0);

        return $kode;
    }
}
