<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Materi_model extends CI_Model {

    protected $table = 'materi';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get all materi for a mapel, ordered by urutan ASC.
     */
    public function get_by_mapel($mapel_id)
    {
        return $this->db
            ->where('mapel_id', $mapel_id)
            ->order_by('urutan', 'ASC')
            ->get($this->table)
            ->result();
    }

    /**
     * Get a single materi by ID.
     */
    public function get_by_id($id)
    {
        return $this->db
            ->where('id', $id)
            ->get($this->table)
            ->row();
    }

    /**
     * Insert new materi.
     */
    public function insert($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    /**
     * Update materi.
     */
    public function update($id, $data)
    {
        $this->db->where('id', $id)->update($this->table, $data);
        return $this->db->affected_rows();
    }

    /**
     * Delete materi.
     */
    public function delete($id)
    {
        $this->db->where('id', $id)->delete($this->table);
        return $this->db->affected_rows();
    }

    /**
     * Get all materi for all mapel owned by a guru.
     */
    public function get_all_by_guru($guru_id)
    {
        return $this->db
            ->select('materi.*, mapel.nama_mapel, kelas.nama_kelas')
            ->from($this->table)
            ->join('mapel', 'mapel.id = materi.mapel_id')
            ->join('kelas', 'kelas.id = mapel.kelas_id', 'left')
            ->where('mapel.guru_id', $guru_id)
            ->order_by('kelas.nama_kelas', 'ASC')
            ->order_by('materi.urutan', 'ASC')
            ->get()
            ->result();
    }

    /**
     * Get the highest urutan value for a mapel (useful when appending).
     */
    public function get_max_urutan($mapel_id)
    {
        $row = $this->db
            ->select_max('urutan')
            ->where('mapel_id', $mapel_id)
            ->get($this->table)
            ->row();
        return $row ? (int) $row->urutan : 0;
    }
}
