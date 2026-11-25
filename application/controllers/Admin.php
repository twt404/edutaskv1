<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends Admin_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->model('Kelas_model');
        $this->load->model('Mapel_model');
        $this->load->model('Tugas_model');
        $this->load->model('Kelompok_model');
        $this->load->library('form_validation');
        $this->load->helper(['url', 'text']);
    }

    // ─── DASHBOARD ──────────────────────────────────────────────────────────

    public function dashboard() {
        $data['title'] = 'Dashboard Admin';
        $data['active'] = 'dashboard';

        // Totals
        $data['total_pengguna'] = $this->db->count_all('users');
        $data['total_guru']     = $this->db->where('role', 'guru')->count_all_results('users');
        $data['total_siswa']    = $this->db->where('role', 'siswa')->count_all_results('users');
        $data['total_kelas']    = $this->db->count_all('kelas');
        $data['total_mapel']    = $this->db->count_all('mapel');
        $data['total_tugas']    = $this->db->count_all('tugas');
        $data['total_submissions'] = $this->db->count_all('pengumpulan_tugas');

        // Recent users
        $data['recent_users'] = $this->db
            ->order_by('created_at', 'DESC')
            ->limit(10)
            ->get('users')
            ->result();

        $this->load->view('admin/layout', $data);
        $this->load->view('admin/dashboard', $data);
        $this->load->view('admin/footer');
    }

    // ─── PENGGUNA ────────────────────────────────────────────────────────────

    public function pengguna() {
        $data['title']  = 'Manajemen Pengguna';
        $data['active'] = 'pengguna';

        $search = $this->input->get('search');
        $role   = $this->input->get('role');
        $tingkat = $this->input->get('tingkat');

        $this->db->select('*')->from('users');

        if ($search) {
            $this->db->group_start()
                     ->like('nama', $search)
                     ->or_like('email', $search)
                     ->group_end();
        }
        if ($role && in_array($role, ['admin', 'guru', 'siswa'])) {
            $this->db->where('role', $role);
        }
        if ($tingkat) {
            $this->db->where('tingkat', $tingkat);
        }

        $this->db->order_by('created_at', 'DESC');

        // Pagination
        $this->load->library('pagination');
        $per_page     = 15;
        $total_rows   = $this->db->count_all_results('', FALSE);
        $offset       = (int)$this->input->get('page') > 1 ? ((int)$this->input->get('page') - 1) * $per_page : 0;

        $config['base_url']    = site_url('admin/pengguna');
        $config['total_rows']  = $total_rows;
        $config['per_page']    = $per_page;
        $config['page_query_string'] = TRUE;
        $config['query_string_segment'] = 'page';
        $config['use_page_numbers'] = TRUE;
        $config['reuse_query_string'] = TRUE;
        $this->pagination->initialize($config);

        $data['users']      = $this->db->limit($per_page, $offset)->get()->result();
        $data['pagination'] = (string) $this->pagination->create_links();
        $data['search']     = $search;
        $data['role_filter'] = $role;
        $data['tingkat_filter'] = $tingkat;
        $data['total_rows'] = $total_rows;

        $data['mapel_counts'] = [];
        $user_ids = array_map(function ($user) {
            return (int) $user->id;
        }, $data['users']);

        if (!empty($user_ids)) {
            $mapel_counts = $this->db
                ->select('guru_id, COUNT(*) AS total')
                ->where_in('guru_id', $user_ids)
                ->group_by('guru_id')
                ->get('mapel')
                ->result();

            foreach ($mapel_counts as $row) {
                $data['mapel_counts'][(int) $row->guru_id] = (int) $row->total;
            }
        }

        $this->load->view('admin/layout', $data);
        $this->load->view('admin/pengguna/index', $data);
        $this->load->view('admin/footer');
    }

    public function tambah_pengguna() {
        $data['title']  = 'Tambah Pengguna';
        $data['active'] = 'pengguna';
        $data['mode']   = 'tambah';
        $data['user']   = null;

        if ($this->input->method() === 'post') {
            $this->form_validation->set_rules('nama',  'Nama',  'required|trim|max_length[100]');
            $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|is_unique[users.email]');
            $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
            $this->form_validation->set_rules('role',  'Role',  'required|in_list[admin,guru,siswa]');
            $this->form_validation->set_rules('tingkat', 'Tingkat', 'trim');
            $this->form_validation->set_rules('jurusan', 'Jurusan', 'trim');

            if ($this->form_validation->run()) {
                $this->db->insert('users', [
                    'nama'       => $this->input->post('nama'),
                    'email'      => $this->input->post('email'),
                    'password'   => md5($this->input->post('password')),
                    'role'       => $this->input->post('role'),
                    'tingkat'    => $this->input->post('tingkat'),
                    'jurusan'    => $this->input->post('jurusan'),
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
                $this->session->set_flashdata('success', 'Pengguna berhasil ditambahkan.');
                redirect('admin/pengguna');
            }
        }

        $this->load->view('admin/layout', $data);
        $this->load->view('admin/pengguna/form', $data);
        $this->load->view('admin/footer');
    }

    public function edit_pengguna($id) {
        $data['title']  = 'Edit Pengguna';
        $data['active'] = 'pengguna';
        $data['mode']   = 'edit';
        $data['user']   = $this->db->get_where('users', ['id' => $id])->row();

        if (!$data['user']) {
            $this->session->set_flashdata('error', 'Pengguna tidak ditemukan.');
            redirect('admin/pengguna');
        }

        if ($this->input->method() === 'post') {
            $this->form_validation->set_rules('nama',  'Nama',  'required|trim|max_length[100]');
            $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email');
            $this->form_validation->set_rules('role',  'Role',  'required|in_list[admin,guru,siswa]');
            $this->form_validation->set_rules('tingkat', 'Tingkat', 'trim');
            $this->form_validation->set_rules('jurusan', 'Jurusan', 'trim');

            // Check email unique except current user
            $email = $this->input->post('email');
            $existing = $this->db->where('email', $email)->where('id !=', $id)->get('users')->row();
            if ($existing) {
                $this->form_validation->set_message('valid_email', 'Email sudah digunakan.');
                $this->session->set_flashdata('error', 'Email sudah digunakan oleh pengguna lain.');
            } elseif ($this->form_validation->run()) {
                $update = [
                    'nama'       => $this->input->post('nama'),
                    'email'      => $email,
                    'role'       => $this->input->post('role'),
                    'tingkat'    => $this->input->post('tingkat'),
                    'jurusan'    => $this->input->post('jurusan'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ];
                $password = $this->input->post('password');
                if (!empty($password)) {
                    $update['password'] = md5($password);
                }
                $this->db->where('id', $id)->update('users', $update);
                $this->session->set_flashdata('success', 'Data pengguna berhasil diperbarui.');
                redirect('admin/pengguna');
            }
        }

        $this->load->view('admin/layout', $data);
        $this->load->view('admin/pengguna/form', $data);
        $this->load->view('admin/footer');
    }

    public function hapus_pengguna($id) {
        if ($this->session->userdata('user_id') == $id) {
            $this->session->set_flashdata('error', 'Anda tidak dapat menghapus akun sendiri.');
            redirect('admin/pengguna');
        }

        $user = $this->db->get_where('users', ['id' => $id])->row();
        if (!$user) {
            $this->session->set_flashdata('error', 'Pengguna tidak ditemukan.');
            redirect('admin/pengguna');
        }

        $force = $this->input->get('force') === '1';

        if ($force) {
            $jumlah_mapel = $this->db
                ->where('guru_id', $id)
                ->count_all_results('mapel');

            $db_debug = $this->db->db_debug;
            $this->db->db_debug = FALSE;

            $this->db->trans_start();

            if ($jumlah_mapel > 0) {
                $this->db->where('guru_id', $id)->delete('mapel');
            }

            $this->db->where('id', $id)->delete('users');
            $this->db->trans_complete();

            $berhasil = $this->db->trans_status();
            $this->db->db_debug = $db_debug;

            if ($berhasil === FALSE) {
                $this->session->set_flashdata('error', 'Pengguna gagal dihapus paksa. Silakan coba lagi.');
                redirect('admin/pengguna');
            }

            $pesan = 'Pengguna berhasil dihapus.';
            if ($jumlah_mapel > 0) {
                $pesan = 'Pengguna beserta ' . $jumlah_mapel . ' mapel dan seluruh data terkait berhasil dihapus.';
            }

            $this->session->set_flashdata('success', $pesan);
            redirect('admin/pengguna');
        }

        $mapel = $this->db
            ->select('nama_mapel')
            ->where('guru_id', $id)
            ->order_by('nama_mapel', 'ASC')
            ->get('mapel')
            ->result();

        if (!empty($mapel)) {
            $nama_mapel = array_map(function ($row) {
                return $row->nama_mapel;
            }, $mapel);
            $ditampilkan = array_slice($nama_mapel, 0, 3);
            $sisa = count($nama_mapel) > 3 ? ' dan ' . (count($nama_mapel) - 3) . ' mapel lainnya' : '';

            $this->session->set_flashdata('error', 'Pengguna tidak dapat dihapus karena masih mengampu mapel: ' . implode(', ', $ditampilkan) . $sisa . '. Hapus mapel tersebut terlebih dahulu, atau gunakan Hapus Paksa.');
            redirect('admin/pengguna');
        }

        $this->db->where('id', $id)->delete('users');
        $this->session->set_flashdata('success', 'Pengguna berhasil dihapus.');
        redirect('admin/pengguna');
    }

    public function hapus_pengguna_batch() {
        if ($this->input->method() !== 'post') {
            redirect('admin/pengguna');
        }

        $ids = $this->input->post('user_ids');
        if (empty($ids) || !is_array($ids)) {
            $this->session->set_flashdata('error', 'Pilih minimal satu pengguna untuk dihapus.');
            redirect('admin/pengguna');
        }

        $current_id = (int) $this->session->userdata('user_id');
        $ids = array_values(array_unique(array_filter(array_map('intval', $ids), function ($id) use ($current_id) {
            return $id > 0 && $id !== $current_id;
        })));

        if (empty($ids)) {
            $this->session->set_flashdata('error', 'Tidak ada pengguna yang dapat dihapus. Akun Anda sendiri tidak dapat dihapus.');
            redirect('admin/pengguna');
        }

        if ($this->input->post('force') === '1') {
            $jumlah_mapel = $this->db
                ->where_in('guru_id', $ids)
                ->count_all_results('mapel');

            $db_debug = $this->db->db_debug;
            $this->db->db_debug = FALSE;

            $this->db->trans_start();

            if ($jumlah_mapel > 0) {
                $this->db->where_in('guru_id', $ids)->delete('mapel');
            }

            $this->db->where_in('id', $ids)->delete('users');
            $deleted = $this->db->affected_rows();
            $this->db->trans_complete();

            $berhasil = $this->db->trans_status();
            $this->db->db_debug = $db_debug;

            if ($berhasil === FALSE) {
                $this->session->set_flashdata('error', 'Pengguna gagal dihapus paksa. Silakan coba lagi.');
                redirect('admin/pengguna');
            }

            if ($deleted < 1) {
                $this->session->set_flashdata('error', 'Tidak ada pengguna yang dihapus. Pengguna mungkin sudah tidak ada.');
                redirect('admin/pengguna');
            }

            $pesan = $deleted . ' pengguna berhasil dihapus.';
            if ($jumlah_mapel > 0) {
                $pesan .= ' ' . $jumlah_mapel . ' mapel beserta materi, tugas, dan data terkait juga dihapus.';
            }

            $this->session->set_flashdata('success', $pesan);
            redirect('admin/pengguna');
        }

        $terkunci = $this->db
            ->select('u.id, u.nama, COUNT(m.id) AS jumlah_mapel', FALSE)
            ->from('users u')
            ->join('mapel m', 'm.guru_id = u.id')
            ->where_in('u.id', $ids)
            ->group_by('u.id')
            ->group_by('u.nama')
            ->order_by('u.nama', 'ASC')
            ->get()
            ->result();

        $id_terkunci = array_map(function ($row) {
            return (int) $row->id;
        }, $terkunci);

        $id_dihapus = array_values(array_diff($ids, $id_terkunci));

        if (empty($id_dihapus)) {
            $this->session->set_flashdata('error', 'Tidak ada pengguna yang dihapus. Masih mengampu mapel: ' . $this->_ringkasan_pengguna_terkunci($terkunci) . ' Gunakan Hapus Paksa untuk menghapus beserta mapel terkait.');
            redirect('admin/pengguna');
        }

        $this->db->where_in('id', $id_dihapus)->delete('users');
        $deleted = $this->db->affected_rows();

        if ($deleted > 0) {
            $pesan = $deleted . ' pengguna berhasil dihapus.';

            if (!empty($terkunci)) {
                $pesan .= ' ' . count($terkunci) . ' pengguna dilewati karena masih mengampu mapel: ' . $this->_ringkasan_pengguna_terkunci($terkunci) . ' Gunakan Hapus Paksa untuk menghapusnya.';
            }

            $this->session->set_flashdata('success', $pesan);
        } else {
            $this->session->set_flashdata('error', 'Pengguna gagal dihapus. Silakan coba lagi.');
        }

        redirect('admin/pengguna');
    }

    private function _ringkasan_pengguna_terkunci($pengguna) {
        $label = array_map(function ($row) {
            return $row->nama . ' (' . (int) $row->jumlah_mapel . ' mapel)';
        }, array_slice($pengguna, 0, 3));

        $sisa = count($pengguna) > 3 ? ' dan ' . (count($pengguna) - 3) . ' lainnya' : '';

        return implode(', ', $label) . $sisa . '.';
    }

    // ─── KELAS (XI/XII) ─────────────────────────────────────────────────────

    public function kelas() {
        $data['title']  = 'Manajemen Kelas';
        $data['active'] = 'kelas';

        $tingkat = $this->input->get('tingkat');
        $data['tingkat_filter'] = $tingkat;
        $data['kelas_list']     = $this->Kelas_model->get_all($tingkat);

        $this->load->view('admin/layout', $data);
        $this->load->view('admin/kelas/index', $data);
        $this->load->view('admin/footer');
    }

    public function tambah_kelas() {
        $data['title']  = 'Tambah Kelas';
        $data['active'] = 'kelas';
        $data['mode']   = 'tambah';
        $data['kelas']  = null;

        if ($this->input->method() === 'post') {
            $this->form_validation->set_rules('nama_kelas', 'Nama Kelas', 'required|trim|max_length[100]');
            $this->form_validation->set_rules('tingkat', 'Tingkat', 'required|in_list[X,XI,XII]');
            $this->form_validation->set_rules('jurusan', 'Jurusan', 'trim|max_length[50]');
            $this->form_validation->set_rules('kode_kelas', 'Kode Kelas', 'trim|max_length[20]');

            if ($this->form_validation->run()) {
                $kode = strtoupper(trim($this->input->post('kode_kelas')));
                if ($kode !== '' && strlen($kode) < 4) {
                    $this->session->set_flashdata('error', 'Kode kelas minimal 4 karakter.');
                } elseif ($kode !== '' && $this->Kelas_model->get_by_kode($kode)) {
                    $this->session->set_flashdata('error', 'Kode kelas sudah digunakan.');
                } else {
                    $this->Kelas_model->insert([
                        'nama_kelas' => $this->input->post('nama_kelas'),
                        'tingkat'    => $this->input->post('tingkat'),
                        'jurusan'    => $this->input->post('jurusan'),
                        'kode_kelas' => $kode,
                    ]);
                    $this->session->set_flashdata('success', 'Kelas berhasil ditambahkan.');
                    redirect('admin/kelas');
                }
            }
        }

        $this->load->view('admin/layout', $data);
        $this->load->view('admin/kelas/form', $data);
        $this->load->view('admin/footer');
    }

    public function edit_kelas($id) {
        $kelas = $this->Kelas_model->get_by_id($id);
        if (!$kelas) {
            $this->session->set_flashdata('error', 'Kelas tidak ditemukan.');
            redirect('admin/kelas');
        }

        $data['title']  = 'Edit Kelas';
        $data['active'] = 'kelas';
        $data['mode']   = 'edit';
        $data['kelas']  = $kelas;

        if ($this->input->method() === 'post') {
            $this->form_validation->set_rules('nama_kelas', 'Nama Kelas', 'required|trim|max_length[100]');
            $this->form_validation->set_rules('tingkat', 'Tingkat', 'required|in_list[X,XI,XII]');
            $this->form_validation->set_rules('jurusan', 'Jurusan', 'trim|max_length[50]');
            $this->form_validation->set_rules('kode_kelas', 'Kode Kelas', 'required|trim|min_length[4]|max_length[20]');

            if ($this->form_validation->run()) {
                $kode    = strtoupper(trim($this->input->post('kode_kelas')));
                $lain    = $this->Kelas_model->get_by_kode($kode);
                if ($lain && (int) $lain->id !== (int) $id) {
                    $this->session->set_flashdata('error', 'Kode kelas sudah digunakan.');
                } else {
                    $this->Kelas_model->update($id, [
                        'nama_kelas' => $this->input->post('nama_kelas'),
                        'tingkat'    => $this->input->post('tingkat'),
                        'jurusan'    => $this->input->post('jurusan'),
                        'kode_kelas' => $kode,
                    ]);
                    $this->session->set_flashdata('success', 'Kelas berhasil diperbarui.');
                    redirect('admin/kelas');
                }
            }
        }

        $this->load->view('admin/layout', $data);
        $this->load->view('admin/kelas/form', $data);
        $this->load->view('admin/footer');
    }

    public function detail_kelas($id) {
        $kelas = $this->Kelas_model->get_with_counts($id);
        if (!$kelas) {
            $this->session->set_flashdata('error', 'Kelas tidak ditemukan.');
            redirect('admin/kelas');
        }

        $data['title']       = 'Detail Kelas: ' . $kelas->nama_kelas;
        $data['active']      = 'kelas';
        $data['kelas']       = $kelas;
        $data['siswa_list']  = $this->Kelas_model->get_siswa($id);
        $data['mapel_list']  = $this->Kelas_model->get_mapel($id);
        $data['kandidat']    = $this->Kelas_model->get_siswa_outside_kelas();

        $this->load->view('admin/layout', $data);
        $this->load->view('admin/kelas/detail', $data);
        $this->load->view('admin/footer');
    }

    public function hapus_kelas($id) {
        $kelas = $this->Kelas_model->get_by_id($id);
        if (!$kelas) {
            $this->session->set_flashdata('error', 'Kelas tidak ditemukan.');
            redirect('admin/kelas');
        }

        if ($this->Kelas_model->count_members($id) > 0 || $this->Kelas_model->count_mapel($id) > 0) {
            $this->session->set_flashdata('error', 'Kelas tidak dapat dihapus karena masih memiliki siswa atau mapel.');
            redirect('admin/kelas');
        }

        $this->Kelas_model->delete($id);
        $this->session->set_flashdata('success', 'Kelas berhasil dihapus.');
        redirect('admin/kelas');
    }

    public function ajax_get_siswa_tersedia() {
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }

        $tingkat = $this->input->get('tingkat');
        $jurusan = $this->input->get('jurusan');
        $search  = $this->input->get('search');

        $siswa = $this->Kelas_model->get_siswa_outside_kelas($tingkat, $jurusan, $search);

        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($siswa));
    }

    public function tambah_siswa_kelas_batch($kelas_id) {
        if ($this->input->method() !== 'post') redirect('admin/kelas');

        $kelas = $this->Kelas_model->get_by_id($kelas_id);
        if (!$kelas) show_404();

        $siswa_ids = $this->input->post('siswa_ids');
        if (empty($siswa_ids) || !is_array($siswa_ids)) {
            $this->session->set_flashdata('error', 'Pilih minimal satu siswa untuk ditambahkan.');
            redirect('admin/detail_kelas/' . $kelas_id);
        }

        $success_count = 0;
        foreach ($siswa_ids as $siswa_id) {
            if ($this->Kelas_model->add_siswa($kelas_id, $siswa_id)) {
                $this->Kelompok_model->auto_assign_for_kelas($kelas_id, $siswa_id);
                $success_count++;
            }
        }

        if ($success_count > 0) {
            $this->Kelas_model->sync_mapel_siswa($kelas_id);
            $this->session->set_flashdata('success', $success_count . ' siswa berhasil ditambahkan ke kelas.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menambahkan siswa atau siswa yang dipilih sudah memiliki kelas.');
        }

        redirect('admin/detail_kelas/' . $kelas_id);
    }

    public function tambah_siswa_kelas($kelas_id) {
        if ($this->input->method() !== 'post') redirect('admin/kelas');

        $kelas = $this->Kelas_model->get_by_id($kelas_id);
        if (!$kelas) show_404();

        $email = $this->input->post('email');
        $siswa = $this->db
            ->where('email', $email)
            ->where('role', 'siswa')
            ->get('users')
            ->row();

        if (!$siswa) {
            $this->session->set_flashdata('error', 'Siswa dengan email tersebut tidak ditemukan.');
        } else {
            $existing = $this->Kelas_model->get_kelas_of_siswa($siswa->id);
            if ($existing) {
                $this->session->set_flashdata('error', 'Siswa sudah terdaftar di kelas ' . $existing->nama_kelas . '.');
            } elseif (!$this->Kelas_model->add_siswa($kelas_id, $siswa->id)) {
                $this->session->set_flashdata('error', 'Gagal menambahkan siswa ke kelas.');
            } else {
                $this->Kelas_model->sync_mapel_siswa($kelas_id);
                $this->Kelompok_model->auto_assign_for_kelas($kelas_id, $siswa->id);
                $this->session->set_flashdata('success', 'Siswa berhasil ditambahkan ke kelas.');
            }
        }

        redirect('admin/detail_kelas/' . $kelas_id);
    }

    public function hapus_siswa_kelas($kelas_id, $siswa_id) {
        $kelas = $this->Kelas_model->get_by_id($kelas_id);
        if (!$kelas) show_404();

        if (!$this->Kelas_model->is_member($kelas_id, $siswa_id)) {
            $this->session->set_flashdata('error', 'Siswa bukan anggota kelas ini.');
            redirect('admin/detail_kelas/' . $kelas_id);
        }

        if ($this->Kelas_model->has_submissions($kelas_id, $siswa_id)) {
            $this->session->set_flashdata('error', 'Siswa tidak dapat dikeluarkan karena sudah memiliki pengumpulan.');
            redirect('admin/detail_kelas/' . $kelas_id);
        }

        $this->Kelas_model->remove_from_groups($kelas_id, $siswa_id);
        $this->Kelas_model->remove_mapel_siswa($kelas_id, $siswa_id);
        $this->Kelas_model->remove_siswa($kelas_id, $siswa_id);
        $this->session->set_flashdata('success', 'Siswa berhasil dikeluarkan dari kelas.');
        redirect('admin/detail_kelas/' . $kelas_id);
    }

    // ─── MAPEL ───────────────────────────────────────────────────────────────

    public function mapel() {
        $data['title']  = 'Manajemen Mapel';
        $data['active'] = 'mapel';

        $this->db->select('m.*, k.nama_kelas, k.tingkat, k.jurusan, u.nama as nama_guru, COUNT(DISTINCT ms.siswa_id) as jumlah_siswa')
                 ->from('mapel m')
                 ->join('users u', 'u.id = m.guru_id', 'left')
                 ->join('kelas k', 'k.id = m.kelas_id', 'left')
                 ->join('mapel_siswa ms', 'ms.mapel_id = m.id', 'left')
                 ->group_by('m.id')
                 ->group_by('k.id')
                 ->order_by('k.tingkat', 'ASC')
                 ->order_by('m.nama_mapel', 'ASC');

        $data['mapel_list'] = $this->db->get()->result();
        $data['kelas_list'] = $this->Kelas_model->get_all();

        $this->load->view('admin/layout', $data);
        $this->load->view('admin/mapel/index', $data);
        $this->load->view('admin/footer');
    }

    public function tetapkan_kelas_mapel($mapel_id) {
        if ($this->input->method() !== 'post') redirect('admin/mapel');

        $mapel = $this->Mapel_model->get_by_id($mapel_id);
        if (!$mapel) {
            $this->session->set_flashdata('error', 'Mapel tidak ditemukan.');
            redirect('admin/mapel');
        }

        $kelas_id = (int) $this->input->post('kelas_id');
        if ($kelas_id < 1) {
            $this->session->set_flashdata('error', 'Pilih kelas terlebih dahulu.');
            redirect('admin/mapel');
        }

        $kelas = $this->Kelas_model->get_by_id($kelas_id);
        if (!$kelas) {
            $this->session->set_flashdata('error', 'Kelas tidak ditemukan.');
            redirect('admin/mapel');
        }

        $duplikat = $this->db
            ->where('kelas_id', $kelas_id)
            ->where('nama_mapel', $mapel->nama_mapel)
            ->where('id !=', $mapel_id)
            ->get('mapel')
            ->row();

        if ($duplikat) {
            $this->session->set_flashdata('error', 'Kelas tersebut sudah memiliki mapel dengan nama yang sama.');
            redirect('admin/mapel');
        }

        $this->db->where('id', $mapel_id)->update('mapel', ['kelas_id' => $kelas_id]);
        $this->Kelas_model->sync_mapel_siswa($kelas_id, $mapel_id);
        $this->session->set_flashdata('success', 'Mapel berhasil ditetapkan ke kelas ' . $kelas->nama_kelas . '.');
        redirect('admin/mapel');
    }

    public function hapus_mapel($id) {
        $mapel = $this->db->get_where('mapel', ['id' => $id])->row();
        if (!$mapel) {
            $this->session->set_flashdata('error', 'Mapel tidak ditemukan.');
            redirect('admin/mapel');
        }
        $this->db->where('id', $id)->delete('mapel');
        $this->session->set_flashdata('success', 'Mapel berhasil dihapus.');
        redirect('admin/mapel');
    }

    // ─── LAPORAN ─────────────────────────────────────────────────────────────

    public function laporan() {
        $data['title']  = 'Laporan';
        $data['active'] = 'laporan';

        $this->db->select('
            m.id,
            m.nama_mapel,
            k.nama_kelas,
            k.tingkat,
            k.jurusan,
            u.nama as nama_guru,
            COUNT(DISTINCT ms.siswa_id)          as jumlah_siswa,
            COUNT(DISTINCT t.id)                 as total_tugas,
            COUNT(DISTINCT pt.id)                as total_pengumpulan,
            COALESCE(AVG(pt.nilai), 0)           as rata_nilai
        ')
        ->from('mapel m')
        ->join('users u',              'u.id = m.guru_id',         'left')
        ->join('kelas k',              'k.id = m.kelas_id',        'left')
        ->join('mapel_siswa ms',       'ms.mapel_id = m.id',       'left')
        ->join('tugas t',              't.mapel_id = m.id',        'left')
        ->join('pengumpulan_tugas pt', 'pt.tugas_id = t.id',       'left')
        ->group_by('m.id')
        ->group_by('k.id')
        ->order_by('k.tingkat', 'ASC')
        ->order_by('k.nama_kelas', 'ASC')
        ->order_by('m.nama_mapel', 'ASC');

        $data['laporan'] = $this->db->get()->result();

        // Summary totals
        $data['grand_siswa']        = $this->db->where('role', 'siswa')->count_all_results('users');
        $data['grand_tugas']        = $this->db->count_all('tugas');
        $data['grand_pengumpulan']  = $this->db->count_all('pengumpulan_tugas');

        $avg_row = $this->db->select('AVG(nilai) as avg_nilai')->get('pengumpulan_tugas')->row();
        $data['grand_avg_nilai'] = $avg_row ? round((float)$avg_row->avg_nilai, 2) : 0;

        $this->load->view('admin/layout', $data);
        $this->load->view('admin/laporan/index', $data);
        $this->load->view('admin/footer');
    }

    // ─── PROFIL ──────────────────────────────────────────────────────────────

    public function profil() {
        $data['title']  = 'Profil Saya';
        $data['active'] = 'profil';
        $user_id        = $this->session->userdata('user_id');
        $data['user']   = $this->db->get_where('users', ['id' => $user_id])->row();

        if ($this->input->method() === 'post') {
            $this->form_validation->set_rules('nama',  'Nama',  'required|trim|max_length[100]');
            $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email');

            if ($this->form_validation->run()) {
                $update = [
                    'nama'       => $this->input->post('nama'),
                    'email'      => $this->input->post('email'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ];
                $password = $this->input->post('password');
                if (!empty($password)) {
                    $update['password'] = md5($password);
                }

                // Handle foto upload
                if (!empty($_FILES['foto']['name'])) {
                    $config_upload = [
                        'upload_path'   => FCPATH . 'uploads/foto/',
                        'allowed_types' => 'gif|jpg|jpeg|png|webp',
                        'max_size'      => 2048,
                        'file_name'     => 'user_' . $user_id . '_' . time(),
                    ];
                    $this->load->library('upload', $config_upload);
                    if ($this->upload->do_upload('foto')) {
                        $update['foto'] = $this->upload->data('file_name');
                    }
                }

                $this->db->where('id', $user_id)->update('users', $update);
                $this->session->set_userdata('nama', $update['nama']);
                $this->session->set_flashdata('success', 'Profil berhasil diperbarui.');
                redirect('admin/profil');
            }
        }

        $this->load->view('admin/layout', $data);
        $this->load->view('admin/profil', $data);
        $this->load->view('admin/footer');
    }

    // ─── SINKRONISASI (1-KLIK PULL / PUSH) ──────────────────────────────────

    public function sync() {
        $data['title']  = 'Sinkronisasi Data';
        $data['active'] = 'sync';

        if ($this->input->method() === 'post') {
            $action     = $this->input->post('action'); // 'pull' or 'push'
            $remote_url = trim($this->input->post('remote_url'));
            $secret_key = trim($this->input->post('secret_key'));

            if (empty($remote_url) || empty($secret_key)) {
                $this->session->set_flashdata('error', 'URL Remote/Hosting dan Secret Key wajib diisi.');
                redirect('admin/sync');
            }

            $this->load->library('syncManager');

            try {
                if ($action === 'pull') {
                    $this->syncmanager->pullFromRemote($remote_url, $secret_key);
                    $this->session->set_flashdata('success', 'Sinkronisasi PULL (Hosting ke Lokal) Berhasil! Data DB & Uploads telah terupdate.');
                } elseif ($action === 'push') {
                    $this->syncmanager->pushToRemote($remote_url, $secret_key);
                    $this->session->set_flashdata('success', 'Sinkronisasi PUSH (Lokal ke Hosting) Berhasil! Data DB & Uploads di hosting telah terupdate.');
                } else {
                    $this->session->set_flashdata('error', 'Aksi sinkronisasi tidak valid.');
                }
            } catch (Exception $e) {
                $this->session->set_flashdata('error', 'Gagal Sinkronisasi: ' . $e->getMessage());
            }

            redirect('admin/sync');
        }

        $this->load->library('syncManager');
        $data['sync_info'] = $this->syncmanager->getSyncInfo();

        $this->load->view('admin/layout', $data);
        $this->load->view('admin/sync/index', $data);
        $this->load->view('admin/footer');
    }
}
