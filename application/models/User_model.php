<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {
    protected $table = 'users';

    public function login($email, $password) {
        $this->db->where('email', $email);
        $this->db->where('password', md5($password));
        return $this->db->get($this->table)->row();
    }

    public function get_all($role = null) {
        if ($role) $this->db->where('role', $role);
        $this->db->order_by('created_at', 'DESC');
        return $this->db->get($this->table)->result();
    }

    public function get_by_id($id) {
        return $this->db->get_where($this->table, ['id' => $id])->row();
    }

    public function count_by_role($role) {
        return $this->db->where('role', $role)->count_all_results($this->table);
    }

    public function insert($data) {
        $data['password'] = md5($data['password']);
        $data['created_at'] = date('Y-m-d H:i:s');
        return $this->db->insert($this->table, $data);
    }

    public function update($id, $data) {
        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = md5($data['password']);
        } else {
            unset($data['password']);
        }
        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }

    public function delete($id) {
        return $this->db->delete($this->table, ['id' => $id]);
    }

    public function email_exists($email, $exclude_id = null) {
        if ($exclude_id) $this->db->where('id !=', $exclude_id);
        return $this->db->get_where($this->table, ['email' => $email])->num_rows() > 0;
    }
}
