<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {
    public function __construct() {
        parent::__construct();
    }
}

class Admin_Controller extends MY_Controller {
    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('logged_in') || $this->session->userdata('role') !== 'admin') {
            redirect('auth/login');
        }
    }
}

class Guru_Controller extends MY_Controller {
    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('logged_in') || $this->session->userdata('role') !== 'guru') {
            redirect('auth/login');
        }
    }
}

class Siswa_Controller extends MY_Controller {
    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('logged_in') || $this->session->userdata('role') !== 'siswa') {
            redirect('auth/login');
        }
    }
}
