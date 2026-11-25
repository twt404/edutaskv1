<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notifikasi_model extends CI_Model {

    protected $table = 'notifikasi';

    public function __construct()
    {
        parent::__construct();
    }

    public function upsert_pengumpulan($guru_id, $tugas_id, $pengumpulan_id, $judul, $pesan)
    {
        $guru_id       = (int) $guru_id;
        $tugas_id      = (int) $tugas_id;
        $pengumpulan_id = (int) $pengumpulan_id;

        if ($guru_id <= 0 || $pengumpulan_id <= 0) {
            return FALSE;
        }

        $db_debug = $this->db->db_debug;
        $this->db->db_debug = FALSE;

        $existing = $this->db
            ->where('user_id', $guru_id)
            ->where('jenis', 'pengumpulan')
            ->where('pengumpulan_id', $pengumpulan_id)
            ->get($this->table)
            ->row();

        $now = date('Y-m-d H:i:s');

        if ($existing) {
            $result = $this->db
                ->where('id', $existing->id)
                ->update($this->table, array(
                    'tugas_id'   => $tugas_id ?: NULL,
                    'judul'      => $judul,
                    'pesan'      => $pesan,
                    'is_read'    => 0,
                    'updated_at' => $now,
                ));
        } else {
            $result = $this->db->insert($this->table, array(
                'user_id'        => $guru_id,
                'tugas_id'       => $tugas_id ?: NULL,
                'pengumpulan_id' => $pengumpulan_id,
                'jenis'          => 'pengumpulan',
                'judul'          => $judul,
                'pesan'          => $pesan,
                'is_read'        => 0,
                'created_at'     => $now,
                'updated_at'     => $now,
            ));
        }

        $this->db->db_debug = $db_debug;

        return $result;
    }

    public function get_by_user($user_id)
    {
        return $this->db
            ->where('user_id', (int) $user_id)
            ->order_by('updated_at', 'DESC')
            ->order_by('created_at', 'DESC')
            ->get($this->table)
            ->result();
    }

    public function count_unread($user_id)
    {
        return (int) $this->db
            ->where('user_id', (int) $user_id)
            ->where('is_read', 0)
            ->count_all_results($this->table);
    }

    public function mark_all_read($user_id)
    {
        return $this->db
            ->where('user_id', (int) $user_id)
            ->where('is_read', 0)
            ->update($this->table, array('is_read' => 1));
    }
}
