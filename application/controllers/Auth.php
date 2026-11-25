<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');
    }

    public function index() {
        $this->login();
    }

    public function login() {
        if ($this->session->userdata('logged_in')) {
            $this->_redirect_by_role($this->session->userdata('role'));
        }
        if ($this->input->post()) {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
            $this->form_validation->set_rules('password', 'Password', 'required');
            if ($this->form_validation->run() === FALSE) {
                $this->session->set_flashdata('error', validation_errors());
                redirect('auth/login');
            }
            $email = $this->input->post('email', TRUE);
            $password = $this->input->post('password', TRUE);
            $user = $this->User_model->login($email, $password);
            if ($user) {
                $sess_data = [
                    'logged_in' => TRUE,
                    'user_id'   => $user->id,
                    'nama'      => $user->nama,
                    'email'     => $user->email,
                    'role'      => $user->role,
                    'foto'      => $user->foto,
                ];
                $this->session->set_userdata($sess_data);
                $this->_redirect_by_role($user->role);
            } else {
                $this->session->set_flashdata('error', 'Email atau password tidak valid!');
                redirect('auth/login');
            }
        }
        $data['title'] = 'Login — EduTask';
        $data['mode'] = 'login';
        $this->load->view('auth/login', $data);
    }

    public function register() {
        if ($this->session->userdata('logged_in')) {
            $this->_redirect_by_role($this->session->userdata('role'));
        }

        $data['title'] = 'Daftar Siswa — EduTask';
        $data['mode'] = 'register';

        if ($this->input->post()) {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('nama', 'Nama Lengkap', 'required|trim|min_length[3]|max_length[100]');
            $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|max_length[100]|is_unique[users.email]');
            $this->form_validation->set_rules('tingkat', 'Tingkat', 'required|trim|in_list[X,XI,XII]');
            $this->form_validation->set_rules('jurusan', 'Jurusan', 'required|trim|min_length[2]|max_length[50]');
            $this->form_validation->set_rules('password', 'Kata Sandi', 'required|min_length[6]|max_length[72]');
            $this->form_validation->set_rules('confirm_password', 'Konfirmasi Kata Sandi', 'required|matches[password]');

            if ($this->form_validation->run() === FALSE) {
                $data['register_error'] = validation_errors();
                $this->load->view('auth/login', $data);
                return;
            }

            $nama = trim($this->input->post('nama', TRUE));
            $email = strtolower(trim($this->input->post('email', TRUE)));
            $tingkat = strtoupper(trim($this->input->post('tingkat', TRUE)));
            $jurusan = strtoupper(trim($this->input->post('jurusan', TRUE)));

            if ($this->User_model->email_exists($email)) {
                $data['register_error'] = 'Email sudah terdaftar. Gunakan email lain atau masuk ke akun Anda.';
                $this->load->view('auth/login', $data);
                return;
            }

            $inserted = $this->User_model->insert([
                'nama'     => $nama,
                'email'    => $email,
                'tingkat'  => $tingkat,
                'jurusan'  => $jurusan,
                'password' => $this->input->post('password', TRUE),
                'role'     => 'siswa',
            ]);

            if ($inserted) {
                $this->session->set_flashdata('success', 'Akun siswa berhasil dibuat. Silakan masuk dengan email dan kata sandi Anda.');
                redirect('auth/login');
            }

            $data['register_error'] = 'Pendaftaran gagal. Silakan coba lagi.';
        }

        $this->load->view('auth/login', $data);
    }

    public function logout() {
        $this->session->sess_destroy();
        redirect('auth/login');
    }

    private function _redirect_by_role($role) {
        switch ($role) {
            case 'admin': redirect('admin/dashboard'); break;
            case 'guru':  redirect('guru/dashboard');  break;
            case 'siswa': redirect('siswa/dashboard'); break;
            default:      redirect('auth/login');
        }
    }
}
