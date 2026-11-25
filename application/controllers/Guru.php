<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Guru Controller
 *
 * Handles all GURU-side functionality for the EduTask e-learning system.
 * Extends Guru_Controller (defined in application/core/MY_Controller.php)
 * which enforces session auth and role='guru'.
 */
class Guru extends Guru_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model(['Mapel_model', 'Kelas_model', 'Tugas_model', 'Materi_model', 'Kelompok_model', 'Notifikasi_model']);
        $this->load->library(['form_validation', 'upload', 'session']);
        $this->load->helper(['url', 'form', 'file', 'text']);
        $this->load->vars(['notif_unread' => $this->Notifikasi_model->count_unread($this->session->userdata('user_id'))]);
    }

    // =========================================================================
    //  DASHBOARD
    // =========================================================================

    public function dashboard()
    {
        $guru_id = $this->session->userdata('user_id');

        // Aggregate stats
        $mapel_list       = $this->Mapel_model->get_by_guru($guru_id);
        $total_mapel      = count($mapel_list);
        $total_tugas      = count($this->Tugas_model->get_all_by_guru($guru_id));

        // Count total distinct siswa across all mapel
        $total_siswa = (int) $this->db
            ->select('COUNT(DISTINCT ms.siswa_id) AS total')
            ->from('mapel_siswa ms')
            ->join('mapel m', 'm.id = ms.mapel_id')
            ->where('m.guru_id', $guru_id)
            ->get()
            ->row()->total;

        $recent_pengumpulan = $this->Tugas_model->get_recent_pengumpulan_for_guru($guru_id, 10);

        $total_materi = (int) $this->db
            ->join('mapel', 'mapel.id = materi.mapel_id')
            ->where('mapel.guru_id', $guru_id)
            ->count_all_results('materi');

        $materi_terbaru = $this->db
            ->select('materi.*, mapel.nama_mapel, kelas.nama_kelas')
            ->from('materi')
            ->join('mapel', 'mapel.id = materi.mapel_id')
            ->join('kelas', 'kelas.id = mapel.kelas_id', 'left')
            ->where('mapel.guru_id', $guru_id)
            ->order_by('materi.created_at', 'DESC')
            ->limit(4)
            ->get()
            ->result();

        $tugas_aktif = $this->db
            ->select('tugas.*, mapel.nama_mapel, kelas.nama_kelas, COUNT(pt.id) AS jumlah_pengumpulan')
            ->from('tugas')
            ->join('mapel', 'mapel.id = tugas.mapel_id')
            ->join('kelas', 'kelas.id = mapel.kelas_id', 'left')
            ->join('pengumpulan_tugas pt', 'pt.tugas_id = tugas.id', 'left')
            ->where('mapel.guru_id', $guru_id)
            ->where('tugas.deadline >=', date('Y-m-d H:i:s'))
            ->group_by('tugas.id')
            ->order_by('tugas.deadline', 'ASC')
            ->limit(4)
            ->get()
            ->result();

        $total_pengumpulan = (int) $this->db
            ->from('pengumpulan_tugas pt')
            ->join('tugas t', 't.id = pt.tugas_id')
            ->join('mapel m', 'm.id = t.mapel_id')
            ->where('m.guru_id', $guru_id)
            ->count_all_results();

        $tepat_waktu = (int) $this->db
            ->from('pengumpulan_tugas pt')
            ->join('tugas t', 't.id = pt.tugas_id')
            ->join('mapel m', 'm.id = t.mapel_id')
            ->where('m.guru_id', $guru_id)
            ->where('pt.status !=', 'terlambat')
            ->count_all_results();

        $tingkat_pengumpulan = $total_pengumpulan > 0
            ? round(($tepat_waktu / $total_pengumpulan) * 100, 1)
            : 0;

        $data = [
            'title'                => 'Dashboard',
            'total_mapel'          => $total_mapel,
            'total_tugas'          => $total_tugas,
            'total_siswa'          => $total_siswa,
            'menunggu_penilaian'   => count($recent_pengumpulan),
            'recent_pengumpulan'   => $recent_pengumpulan,
            'mapel_list'           => $mapel_list,
            'total_materi'         => $total_materi,
            'materi_terbaru'       => $materi_terbaru,
            'tugas_aktif'          => $tugas_aktif,
            'total_pengumpulan'    => $total_pengumpulan,
            'tingkat_pengumpulan'  => $tingkat_pengumpulan,
            'guru'                 => $this->session->userdata(),
        ];

        $data['content_view'] = $this->load->view('guru/dashboard', $data, TRUE);
        $this->load->view('guru/layout', $data);
    }

    // =========================================================================
    //  MAPEL
    // =========================================================================

    public function mapel()
    {
        $guru_id    = $this->session->userdata('user_id');
        $mapel_list = $this->Mapel_model->get_by_guru($guru_id);

        $data = [
            'title'      => 'Mapel Saya',
            'mapel_list' => $mapel_list,
        ];

        $data['content_view'] = $this->load->view('guru/mapel/index', $data, TRUE);
        $this->load->view('guru/layout', $data);
    }

    public function tambah_mapel()
    {
        if ($this->input->method() === 'post') {
            $this->form_validation->set_rules('nama_mapel', 'Nama Mata Pelajaran', 'required|min_length[3]|max_length[150]');
            $this->form_validation->set_rules('deskripsi', 'Deskripsi', 'trim');
            $this->form_validation->set_rules('kelas_id', 'Kelas', 'required|integer');

            if ($this->form_validation->run()) {
                $kelas_id = (int) $this->input->post('kelas_id');
                $kelas    = $this->Kelas_model->get_by_id($kelas_id);
                $nama     = trim($this->input->post('nama_mapel'));

                if ( ! $kelas) {
                    $this->session->set_flashdata('error', 'Kelas tidak ditemukan.');
                } else {
                    $duplikat = $this->db
                        ->where('kelas_id', $kelas_id)
                        ->where('nama_mapel', $nama)
                        ->count_all_results('mapel');

                    if ($duplikat > 0) {
                        $this->session->set_flashdata('error', 'Kelas tersebut sudah memiliki mata pelajaran dengan nama yang sama.');
                    } else {
                        $mapel_id = $this->Mapel_model->insert([
                            'nama_mapel' => $nama,
                            'deskripsi'  => $this->input->post('deskripsi'),
                            'guru_id'    => $this->session->userdata('user_id'),
                            'kelas_id'   => $kelas_id,
                        ]);
                        $this->Kelas_model->sync_mapel_siswa($kelas_id, $mapel_id);
                        $this->session->set_flashdata('success', 'Mapel berhasil ditambahkan.');
                        redirect('guru/mapel');
                    }
                }
            }
        }

        $data = [
            'title'      => 'Tambah Mapel',
            'kelas_list' => $this->Kelas_model->get_all(),
        ];
        $data['content_view'] = $this->load->view('guru/mapel/form', $data, TRUE);
        $this->load->view('guru/layout', $data);
    }

    public function edit_mapel($id)
    {
        $mapel = $this->Mapel_model->get_by_id($id);
        if ( ! $mapel || $mapel->guru_id != $this->session->userdata('user_id')) {
            show_error('Mapel tidak ditemukan atau Anda tidak memiliki akses.', 403);
        }

        if ($this->input->method() === 'post') {
            $this->form_validation->set_rules('nama_mapel', 'Nama Mata Pelajaran', 'required|min_length[3]|max_length[150]');
            $this->form_validation->set_rules('deskripsi', 'Deskripsi', 'trim');

            if ($this->form_validation->run()) {
                $nama = trim($this->input->post('nama_mapel'));

                $duplikat = $this->db
                    ->where('kelas_id', $mapel->kelas_id)
                    ->where('nama_mapel', $nama)
                    ->where('id !=', $id)
                    ->count_all_results('mapel');

                if ($duplikat > 0) {
                    $this->session->set_flashdata('error', 'Kelas tersebut sudah memiliki mata pelajaran dengan nama yang sama.');
                } else {
                    $this->Mapel_model->update($id, [
                        'nama_mapel' => $nama,
                        'deskripsi'  => $this->input->post('deskripsi'),
                    ]);
                    $this->session->set_flashdata('success', 'Mapel berhasil diperbarui.');
                    redirect('guru/mapel');
                }
            }
        }

        $data = ['title' => 'Edit Mapel', 'mapel' => $mapel];
        $data['content_view'] = $this->load->view('guru/mapel/form', $data, TRUE);
        $this->load->view('guru/layout', $data);
    }

    public function hapus_mapel($id)
    {
        $mapel = $this->Mapel_model->get_by_id($id);
        if ( ! $mapel || $mapel->guru_id != $this->session->userdata('user_id')) {
            show_error('Mapel tidak ditemukan atau Anda tidak memiliki akses.', 403);
        }
        $this->Mapel_model->delete($id);
        $this->session->set_flashdata('success', 'Mapel berhasil dihapus.');
        redirect('guru/mapel');
    }

    public function ajax_get_siswa_tersedia() {
        if (!$this->input->is_ajax_request()) exit('No direct script access allowed');
        $tingkat = $this->input->get('tingkat');
        $jurusan = $this->input->get('jurusan');
        $search  = $this->input->get('search');
        $siswa = $this->Kelas_model->get_siswa_outside_kelas($tingkat, $jurusan, $search);
        return $this->output->set_content_type('application/json')->set_output(json_encode($siswa));
    }

    public function tambah_siswa_batch($kelas_id) {
        if ($this->input->method() !== 'post') redirect('guru/mapel');
        // Simple verify: Guru must teach at least one mapel in this class
        $can_access = $this->db->where('kelas_id', $kelas_id)->where('guru_id', $this->session->userdata('user_id'))->count_all_results('mapel') > 0;
        if (!$can_access) show_error('Akses ditolak.', 403);

        $siswa_ids = $this->input->post('siswa_ids');
        if (empty($siswa_ids)) {
            $this->session->set_flashdata('error', 'Pilih siswa.');
            redirect($_SERVER['HTTP_REFERER']);
        }

        $success_count = 0;
        foreach ($siswa_ids as $siswa_id) {
            if ($this->Kelas_model->add_siswa($kelas_id, $siswa_id)) {
                $this->Kelompok_model->auto_assign_for_kelas($kelas_id, $siswa_id);
                $success_count++;
            }
        }
        $this->Kelas_model->sync_mapel_siswa($kelas_id);
        $this->session->set_flashdata('success', $success_count . ' siswa berhasil ditambahkan.');
        redirect($_SERVER['HTTP_REFERER']);
    }

    public function detail_mapel($id)
    {
        $mapel = $this->Mapel_model->get_with_siswa_count($id);
        if ( ! $mapel || $mapel->guru_id != $this->session->userdata('user_id')) {
            show_error('Mapel tidak ditemukan atau Anda tidak memiliki akses.', 403);
        }

        $siswa_list  = $this->Mapel_model->get_siswa($id);
        $materi_list = $this->Materi_model->get_by_mapel($id);
        $tugas_list  = $this->Tugas_model->get_by_mapel($id);

        $data = [
            'title'       => 'Detail Mapel: ' . $mapel->nama_mapel,
            'mapel'       => $mapel,
            'siswa_list'  => $siswa_list,
            'materi_list' => $materi_list,
            'tugas_list'  => $tugas_list,
        ];

        $data['content_view'] = $this->load->view('guru/mapel/detail', $data, TRUE);
        $this->load->view('guru/layout', $data);
    }

    // Penambahan/pengeluaran siswa dilakukan oleh Admin melalui menu Kelas.

    // =========================================================================
    //  MATERI
    // =========================================================================

    public function materi()
    {
        $guru_id     = $this->session->userdata('user_id');
        $materi_list = $this->Materi_model->get_all_by_guru($guru_id);

        $data = [
            'title'       => 'Materi',
            'materi_list' => $materi_list,
        ];

        $data['content_view'] = $this->load->view('guru/materi/index', $data, TRUE);
        $this->load->view('guru/layout', $data);
    }

    public function tambah_materi($mapel_id)
    {
        $mapel = $this->Mapel_model->get_by_id($mapel_id);
        if ( ! $mapel || $mapel->guru_id != $this->session->userdata('user_id')) {
            show_error('Mapel tidak ditemukan atau Anda tidak memiliki akses.', 403);
        }

        if ($this->input->method() === 'post') {
            $this->form_validation->set_rules('judul', 'Judul', 'required|max_length[200]');
            $this->form_validation->set_rules('deskripsi', 'Deskripsi', 'trim');

            if ($this->form_validation->run()) {
                $file_materi = NULL;

                if ( ! empty($_FILES['file_materi']['name'])) {
                    $upload_path = FCPATH . 'uploads/materi/';
                    if ( ! is_dir($upload_path)) mkdir($upload_path, 0755, TRUE);

                    $this->upload->initialize([
                        'upload_path'   => $upload_path,
                        'allowed_types' => 'pdf|doc|docx|ppt|pptx|xls|xlsx|zip|png|jpg|jpeg',
                        'max_size'      => 20480, // 20 MB
                        'encrypt_name'  => TRUE,
                    ]);

                    if ($this->upload->do_upload('file_materi')) {
                        $file_materi = $this->upload->data('file_name');
                    } else {
                        $this->session->set_flashdata('error', $this->upload->display_errors('', ''));
                        redirect('guru/tambah_materi/' . $mapel_id);
                        return;
                    }
                }

                $max_urutan = $this->Materi_model->get_max_urutan($mapel_id);
                $this->Materi_model->insert([
                    'mapel_id'    => $mapel_id,
                    'judul'       => $this->input->post('judul'),
                    'deskripsi'   => $this->input->post('deskripsi'),
                    'file_materi' => $file_materi,
                    'urutan'      => $max_urutan + 1,
                ]);
                $this->session->set_flashdata('success', 'Materi berhasil ditambahkan.');
                redirect('guru/detail_mapel/' . $mapel_id);
            }
        }

        $data = ['title' => 'Tambah Materi', 'mapel' => $mapel];
        $data['content_view'] = $this->load->view('guru/materi/form', $data, TRUE);
        $this->load->view('guru/layout', $data);
    }

    public function edit_materi($id)
    {
        $materi = $this->Materi_model->get_by_id($id);
        if ( ! $materi) show_error('Materi tidak ditemukan.', 404);

        $mapel = $this->Mapel_model->get_by_id($materi->mapel_id);
        if ( ! $mapel || $mapel->guru_id != $this->session->userdata('user_id')) {
            show_error('Anda tidak memiliki akses ke materi ini.', 403);
        }

        if ($this->input->method() === 'post') {
            $this->form_validation->set_rules('judul', 'Judul', 'required|max_length[200]');

            if ($this->form_validation->run()) {
                $update_data = [
                    'judul'     => $this->input->post('judul'),
                    'deskripsi' => $this->input->post('deskripsi'),
                    'urutan'    => (int) $this->input->post('urutan'),
                ];

                if ( ! empty($_FILES['file_materi']['name'])) {
                    $upload_path = FCPATH . 'uploads/materi/';
                    if ( ! is_dir($upload_path)) mkdir($upload_path, 0755, TRUE);

                    $this->upload->initialize([
                        'upload_path'   => $upload_path,
                        'allowed_types' => 'pdf|doc|docx|ppt|pptx|xls|xlsx|zip|png|jpg|jpeg',
                        'max_size'      => 20480,
                        'encrypt_name'  => TRUE,
                    ]);

                    if ($this->upload->do_upload('file_materi')) {
                        // Delete old file
                        if ($materi->file_materi) {
                            @unlink($upload_path . $materi->file_materi);
                        }
                        $update_data['file_materi'] = $this->upload->data('file_name');
                    } else {
                        $this->session->set_flashdata('error', $this->upload->display_errors('', ''));
                    }
                }

                $this->Materi_model->update($id, $update_data);
                $this->session->set_flashdata('success', 'Materi berhasil diperbarui.');
                redirect('guru/detail_mapel/' . $mapel->id);
            }
        }

        $data = ['title' => 'Edit Materi', 'materi' => $materi, 'mapel' => $mapel];
        $data['content_view'] = $this->load->view('guru/materi/form', $data, TRUE);
        $this->load->view('guru/layout', $data);
    }

    public function hapus_materi($id)
    {
        $materi = $this->Materi_model->get_by_id($id);
        if ( ! $materi) show_error('Materi tidak ditemukan.', 404);

        $mapel = $this->Mapel_model->get_by_id($materi->mapel_id);
        if ( ! $mapel || $mapel->guru_id != $this->session->userdata('user_id')) {
            show_error('Anda tidak memiliki akses.', 403);
        }

        if ($materi->file_materi) {
            @unlink(FCPATH . 'uploads/materi/' . $materi->file_materi);
        }

        $this->Materi_model->delete($id);
        $this->session->set_flashdata('success', 'Materi berhasil dihapus.');
        redirect('guru/detail_mapel/' . $mapel->id);
    }

    // =========================================================================
    //  TUGAS
    // =========================================================================

    public function tugas()
    {
        $guru_id    = $this->session->userdata('user_id');
        $tugas_list = $this->Tugas_model->get_all_by_guru($guru_id);

        $data = [
            'title'      => 'Tugas',
            'tugas_list' => $tugas_list,
            'mapel_list' => $this->Mapel_model->get_by_guru($guru_id),
        ];

        $data['content_view'] = $this->load->view('guru/tugas/index', $data, TRUE);
        $this->load->view('guru/layout', $data);
    }

    public function tambah_tugas($mapel_id)
    {
        $mapel = $this->Mapel_model->get_by_id($mapel_id);
        if ( ! $mapel || $mapel->guru_id != $this->session->userdata('user_id')) {
            show_error('Mapel tidak ditemukan atau Anda tidak memiliki akses.', 403);
        }

        if ($this->input->method() === 'post') {
            $jenis = $this->input->post('jenis');
            $status = $this->input->post('status');

            $this->form_validation->set_rules('judul', 'Judul Tugas', 'required|max_length[200]');
            $this->form_validation->set_rules('deskripsi', 'Deskripsi', 'trim');
            $this->form_validation->set_rules('jenis', 'Jenis', 'required|in_list[individu,kelompok]');
            $this->form_validation->set_rules('deadline', 'Deadline', 'required');
            $this->form_validation->set_rules('status', 'Status Tugas', 'required|in_list[draft,published,scheduled]');
            if ($status === 'scheduled') {
                $this->form_validation->set_rules('publish_at', 'Tanggal Publikasi', 'required');
            }

            if ($jenis === 'kelompok') {
                $this->form_validation->set_rules('mode_kelompok', 'Mode Kelompok', 'required|in_list[acak,guru,siswa]');
                $this->form_validation->set_rules('anggota_per_kelompok', 'Anggota per Kelompok', 'required|integer|greater_than_equal_to[2]|less_than_equal_to[10]');
                $this->form_validation->set_rules('jumlah_kelompok', 'Jumlah Kelompok', 'required|integer|greater_than_equal_to[1]');
            }

            if ($this->form_validation->run()) {
                $file_tugas = NULL;

                if ( ! empty($_FILES['file_tugas']['name'])) {
                    $upload_path = FCPATH . 'uploads/tugas/';
                    if ( ! is_dir($upload_path)) mkdir($upload_path, 0755, TRUE);

                    $this->upload->initialize([
                        'upload_path'   => $upload_path,
                        'allowed_types' => 'pdf|doc|docx|ppt|pptx|zip|png|jpg',
                        'max_size'      => 20480,
                        'encrypt_name'  => TRUE,
                    ]);

                    if ($this->upload->do_upload('file_tugas')) {
                        $file_tugas = $this->upload->data('file_name');
                    }
                }

                $data = [
                    'mapel_id'   => $mapel_id,
                    'judul'      => $this->input->post('judul'),
                    'deskripsi'  => $this->input->post('deskripsi'),
                    'jenis'      => $jenis,
                    'deadline'   => $this->input->post('deadline'),
                    'status'     => $status,
                    'publish_at' => $this->_publish_at_from_post($status),
                    'file_tugas' => $file_tugas,
                ];

                $jumlah_kelompok = 0;
                $anggota_per     = 0;

                if ($jenis === 'kelompok') {
                    $anggota_per = max(2, min(10, (int) $this->input->post('anggota_per_kelompok')));
                    $jumlah_kelompok = max(1, (int) $this->input->post('jumlah_kelompok'));

                    $total_siswa = (int) $this->Mapel_model->get_with_siswa_count($mapel_id)->jumlah_siswa;
                    if ($total_siswa > 0) {
                        $jumlah_kelompok = max($jumlah_kelompok, (int) ceil($total_siswa / $anggota_per));
                    }

                    $data['mode_kelompok']        = $this->input->post('mode_kelompok');
                    $data['anggota_per_kelompok'] = $anggota_per;
                    $data['allow_revisi']         = $this->input->post('allow_revisi') ? 1 : 0;
                    $data['wajib_kontribusi']     = $this->input->post('wajib_kontribusi') ? 1 : 0;
                    $data['kelompok_locked']      = 0;
                }

                $tugas_id = $this->Tugas_model->insert($data);

                if ($jenis === 'individu') {
                    $this->Tugas_model->save_penerima($tugas_id, $this->input->post('siswa_ids'));
                }

                $this->_upload_lampiran_tugas($tugas_id);

                $this->Tugas_model->sync_items($tugas_id, $this->_collect_items_from_post());

                if ($jenis === 'kelompok') {
                    if ($data['mode_kelompok'] === 'acak') {
                        $this->Kelompok_model->assign_random($tugas_id, $mapel_id, $jumlah_kelompok, $anggota_per);
                    } else {
                        $this->Kelompok_model->create_groups($tugas_id, $jumlah_kelompok);
                    }
                }

                $this->session->set_flashdata('success', 'Tugas berhasil ditambahkan.');
                redirect('guru/detail_mapel/' . $mapel_id);
            }
        }

        $data = [
            'title'    => 'Tambah Tugas',
            'mapel'    => $mapel,
            'items'    => $this->_items_seed_from_post(),
            'students' => $this->Mapel_model->get_siswa($mapel_id),
            'penerima' => (array) $this->input->post('siswa_ids'),
            'lampiran' => [],
        ];
        $data['content_view'] = $this->load->view('guru/tugas/form', $data, TRUE);
        $this->load->view('guru/layout', $data);
    }

    public function edit_tugas($id)
    {
        $tugas = $this->Tugas_model->get_by_id($id);
        if ( ! $tugas) show_error('Tugas tidak ditemukan.', 404);

        $mapel = $this->Mapel_model->get_by_id($tugas->mapel_id);
        if ( ! $mapel || $mapel->guru_id != $this->session->userdata('user_id')) {
            show_error('Anda tidak memiliki akses.', 403);
        }

        if ($this->input->method() === 'post') {
            $status = $this->input->post('status');

            $this->form_validation->set_rules('judul', 'Judul Tugas', 'required|max_length[200]');
            $this->form_validation->set_rules('deadline', 'Deadline', 'required');
            $this->form_validation->set_rules('status', 'Status Tugas', 'required|in_list[draft,published,scheduled]');
            if ($status === 'scheduled') {
                $this->form_validation->set_rules('publish_at', 'Tanggal Publikasi', 'required');
            }

            if ($this->form_validation->run()) {
                $update_data = [
                    'judul'      => $this->input->post('judul'),
                    'deskripsi'  => $this->input->post('deskripsi'),
                    'deadline'   => $this->input->post('deadline'),
                    'status'     => $status,
                    'publish_at' => $this->_publish_at_from_post($status),
                ];

                if ( ! empty($_FILES['file_tugas']['name'])) {
                    $upload_path = FCPATH . 'uploads/tugas/';
                    if ( ! is_dir($upload_path)) mkdir($upload_path, 0755, TRUE);

                    $this->upload->initialize([
                        'upload_path'   => $upload_path,
                        'allowed_types' => 'pdf|doc|docx|ppt|pptx|zip|png|jpg',
                        'max_size'      => 20480,
                        'encrypt_name'  => TRUE,
                    ]);

                    if ($this->upload->do_upload('file_tugas')) {
                        if ($tugas->file_tugas) {
                            @unlink($upload_path . $tugas->file_tugas);
                        }
                        $update_data['file_tugas'] = $this->upload->data('file_name');
                    }
                }

                $this->Tugas_model->update($id, $update_data);
                
                if ($tugas->jenis === 'individu') {
                    $this->Tugas_model->save_penerima($id, $this->input->post('siswa_ids'));
                }

                $this->_upload_lampiran_tugas($id);

                $this->Tugas_model->sync_items($id, $this->_collect_items_from_post());
                $this->session->set_flashdata('success', 'Tugas berhasil diperbarui.');
                redirect('guru/detail_tugas/' . $id);
            }
        }

        $data = [
            'title'    => 'Edit Tugas',
            'tugas'    => $tugas,
            'mapel'    => $mapel,
            'students' => $this->Mapel_model->get_siswa($tugas->mapel_id),
            'penerima' => $this->input->method() === 'post'
                ? (array) $this->input->post('siswa_ids')
                : array_column($this->Tugas_model->get_penerima($id), 'siswa_id'),
            'lampiran' => $this->Tugas_model->get_lampiran($id),
            'items'    => $this->input->method() === 'post'
                ? $this->_items_seed_from_post()
                : $this->Tugas_model->get_items($id),
        ];
        $data['content_view'] = $this->load->view('guru/tugas/form', $data, TRUE);
        $this->load->view('guru/layout', $data);
    }

    public function hapus_tugas($id)
    {
        $tugas = $this->Tugas_model->get_by_id($id);
        if ( ! $tugas) show_error('Tugas tidak ditemukan.', 404);

        $mapel = $this->Mapel_model->get_by_id($tugas->mapel_id);
        if ( ! $mapel || $mapel->guru_id != $this->session->userdata('user_id')) {
            show_error('Anda tidak memiliki akses.', 403);
        }

        if ($tugas->file_tugas) {
            @unlink(FCPATH . 'uploads/tugas/' . $tugas->file_tugas);
        }

        foreach ($this->Tugas_model->get_lampiran($id) as $lampiran) {
            @unlink(FCPATH . 'uploads/tugas/' . $lampiran->nama_file);
        }

        $this->Tugas_model->delete($id);
        $this->session->set_flashdata('success', 'Tugas berhasil dihapus.');
        redirect('guru/detail_mapel/' . $mapel->id);
    }

    public function hapus_lampiran($id)
    {
        $lampiran = $this->Tugas_model->get_lampiran_by_id($id);
        if ( ! $lampiran) show_error('Lampiran tidak ditemukan.', 404);

        $tugas = $this->Tugas_model->get_by_id($lampiran->tugas_id);
        $mapel = $this->Mapel_model->get_by_id($tugas->mapel_id);
        if ( ! $mapel || $mapel->guru_id != $this->session->userdata('user_id')) {
            show_error('Anda tidak memiliki akses.', 403);
        }

        $this->Tugas_model->delete_lampiran($id);
        $this->session->set_flashdata('success', 'Lampiran berhasil dihapus.');
        redirect('guru/edit_tugas/' . $tugas->id);
    }

    private function _publish_at_from_post($status)
    {
        if ($status !== 'scheduled') {
            return NULL;
        }

        $value = trim((string) $this->input->post('publish_at'));
        if ($value === '') {
            return NULL;
        }

        $value = str_replace('T', ' ', $value);
        if (strlen($value) === 16) {
            $value .= ':00';
        }

        return $value;
    }

    private function _upload_lampiran_tugas($tugas_id)
    {
        if (empty($_FILES['lampiran']['name'][0])) {
            return;
        }

        $upload_path = FCPATH . 'uploads/tugas/';
        if ( ! is_dir($upload_path)) {
            mkdir($upload_path, 0755, TRUE);
        }

        $files  = $_FILES['lampiran'];
        $errors = array();

        for ($i = 0; $i < count($files['name']); $i++) {
            if ($files['name'][$i] === '') {
                continue;
            }

            $original = $files['name'][$i];

            if ((int) $files['error'][$i] !== UPLOAD_ERR_OK) {
                $errors[] = $original . ': ' . $this->_upload_error_text((int) $files['error'][$i]);
                continue;
            }

            $_FILES['lampiran_temp'] = array(
                'name'     => $original,
                'type'     => $files['type'][$i],
                'tmp_name' => $files['tmp_name'][$i],
                'error'    => $files['error'][$i],
                'size'     => $files['size'][$i],
            );

            $this->upload->initialize(array(
                'upload_path'   => $upload_path,
                'allowed_types' => 'pdf|doc|docx|ppt|pptx|xls|xlsx|zip|rar|7z|png|jpg|jpeg|webp',
                'max_size'      => 20480,
                'encrypt_name'  => TRUE,
            ));

            if ( ! $this->upload->do_upload('lampiran_temp')) {
                $errors[] = $original . ': ' . trim(strip_tags($this->upload->display_errors('', '')));
                continue;
            }

            $up = $this->upload->data();
            if ( ! $this->Tugas_model->save_lampiran($tugas_id, $up['file_name'], $original, $up['file_size'])) {
                @unlink($upload_path . $up['file_name']);
                $errors[] = $original . ': gagal disimpan ke database.';
            }
        }

        unset($_FILES['lampiran_temp']);

        if ( ! empty($errors)) {
            $this->session->set_flashdata('error', 'Lampiran gagal diunggah: ' . implode(' | ', $errors));
        }
    }

    private function _upload_error_text($code)
    {
        switch ($code) {
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                return 'ukuran berkas melebihi batas server.';
            case UPLOAD_ERR_PARTIAL:
                return 'berkas hanya terunggah sebagian.';
            case UPLOAD_ERR_NO_FILE:
                return 'tidak ada berkas dipilih.';
            case UPLOAD_ERR_NO_TMP_DIR:
                return 'folder temporary tidak tersedia.';
            case UPLOAD_ERR_CANT_WRITE:
                return 'server gagal menulis berkas.';
            case UPLOAD_ERR_EXTENSION:
                return 'upload dihentikan oleh ekstensi PHP.';
            default:
                return 'kesalahan upload tidak diketahui.';
        }
    }

    public function detail_tugas($id)
    {
        $tugas = $this->Tugas_model->get_with_submissions_count($id);
        if ( ! $tugas) show_error('Tugas tidak ditemukan.', 404);

        $mapel = $this->Mapel_model->get_by_id($tugas->mapel_id);
        if ( ! $mapel || $mapel->guru_id != $this->session->userdata('user_id')) {
            show_error('Anda tidak memiliki akses.', 403);
        }

        $submissions = $this->Tugas_model->get_pengumpulan_by_tugas($id);
        $items       = $this->Tugas_model->get_items($id);
        $file_rows   = $this->Tugas_model->get_files_by_tugas($id);
        $penerima    = $this->Tugas_model->get_penerima($id);

        $submission_rows     = $submissions;
        $submitted_count     = count($submissions);
        $not_submitted_count = 0;

        if ($tugas->jenis === 'individu') {
            $siswa_wajib = array();
            if ( ! empty($penerima)) {
                foreach ($penerima as $p) {
                    $siswa_wajib[] = (object) array(
                        'id'    => (int) $p->siswa_id,
                        'nama'  => $p->nama,
                        'email' => $p->email,
                    );
                }
            } else {
                $siswa_wajib = $this->Mapel_model->get_siswa($tugas->mapel_id);
            }

            $sub_by_siswa = array();
            foreach ($submissions as $submission) {
                if ( ! empty($submission->siswa_id)) {
                    $sub_by_siswa[(int) $submission->siswa_id] = $submission;
                }
            }

            $submission_rows = array();
            $submitted_count = 0;

            foreach ($siswa_wajib as $siswa) {
                $siswa_id = (int) $siswa->id;

                if (isset($sub_by_siswa[$siswa_id])) {
                    $row = $sub_by_siswa[$siswa_id];
                    unset($sub_by_siswa[$siswa_id]);

                    $row->is_submitted = TRUE;
                    if (empty($row->nama_siswa)) {
                        $row->nama_siswa = $siswa->nama;
                    }
                    if (empty($row->email_siswa)) {
                        $row->email_siswa = $siswa->email;
                    }

                    $submission_rows[] = $row;
                    $submitted_count++;
                } else {
                    $submission_rows[] = (object) array(
                        'id'               => NULL,
                        'tugas_id'         => (int) $id,
                        'siswa_id'         => $siswa_id,
                        'nama_siswa'       => $siswa->nama,
                        'email_siswa'      => $siswa->email,
                        'file_jawaban'     => NULL,
                        'catatan'          => NULL,
                        'status'           => 'belum',
                        'nilai'            => NULL,
                        'nilai_sebelumnya' => NULL,
                        'feedback'         => NULL,
                        'submitted_at'     => NULL,
                        'is_submitted'     => FALSE,
                    );
                    $not_submitted_count++;
                }
            }

            foreach ($sub_by_siswa as $row) {
                $row->is_submitted = TRUE;
                $submission_rows[] = $row;
                $submitted_count++;
            }

            usort($submission_rows, function ($a, $b) {
                $a_submitted = ! empty($a->is_submitted) ? 1 : 0;
                $b_submitted = ! empty($b->is_submitted) ? 1 : 0;

                if ($a_submitted !== $b_submitted) {
                    return $a_submitted - $b_submitted;
                }

                return strcasecmp((string) $a->nama_siswa, (string) $b->nama_siswa);
            });
        }

        $files_by_pengumpulan = array();
        foreach ($file_rows as $file) {
            $pid = (int) $file->pengumpulan_id;
            if ( ! isset($files_by_pengumpulan[$pid])) {
                $files_by_pengumpulan[$pid] = array();
            }
            $files_by_pengumpulan[$pid][] = $file;
        }

        $groups       = array();
        $sub_by_group = array();

        if ($tugas->jenis === 'kelompok') {
            $groups = $this->Kelompok_model->get_with_members($id);
            foreach ($submissions as $s) {
                if ($s->kelompok_id) {
                    $sub_by_group[(int) $s->kelompok_id] = $s;
                }
            }
        }

        $data = [
            'title'               => 'Detail Tugas: ' . $tugas->judul,
            'tugas'               => $tugas,
            'mapel'               => $mapel,
            'submissions'         => $submissions,
            'submission_rows'     => $submission_rows,
            'submitted_count'     => $submitted_count,
            'not_submitted_count' => $not_submitted_count,
            'total_siswa'         => count($submission_rows),
            'groups'              => $groups,
            'sub_by_group'        => $sub_by_group,
            'items'               => $items,
            'lampiran'            => $this->Tugas_model->get_lampiran($id),
            'penerima'            => $penerima,
            'files_by_pengumpulan' => $files_by_pengumpulan,
        ];

        $data['content_view'] = $this->load->view('guru/tugas/detail', $data, TRUE);
        $this->load->view('guru/layout', $data);
    }

    public function beri_nilai($pengumpulan_id)
    {
        if ($this->input->method() !== 'post') redirect('guru/tugas');

        $pengumpulan = $this->db->where('id', $pengumpulan_id)->get('pengumpulan_tugas')->row();
        if ( ! $pengumpulan) show_error('Data pengumpulan tidak ditemukan.', 404);

        // Verify ownership
        $tugas = $this->Tugas_model->get_by_id($pengumpulan->tugas_id);
        $mapel = $this->Mapel_model->get_by_id($tugas->mapel_id);
        if ( ! $mapel || $mapel->guru_id != $this->session->userdata('user_id')) {
            show_error('Anda tidak memiliki akses.', 403);
        }

        $this->form_validation->set_rules('nilai', 'Nilai', 'required|numeric|greater_than_equal_to[0]|less_than_equal_to[100]');
        $this->form_validation->set_rules('feedback', 'Feedback', 'trim');

        if ($this->form_validation->run()) {
            $this->Tugas_model->beri_nilai(
                $pengumpulan_id,
                $this->input->post('nilai'),
                $this->input->post('feedback')
            );
            $this->session->set_flashdata('success', 'Nilai berhasil diberikan.');
        } else {
            $this->session->set_flashdata('error', validation_errors('', ''));
        }

        redirect('guru/detail_tugas/' . $pengumpulan->tugas_id);
    }

    public function minta_revisi($pengumpulan_id)
    {
        if ($this->input->method() !== 'post') redirect('guru/tugas');

        $pengumpulan = $this->Tugas_model->get_pengumpulan_by_id($pengumpulan_id);
        if ( ! $pengumpulan) show_error('Data pengumpulan tidak ditemukan.', 404);

        $tugas = $this->Tugas_model->get_by_id($pengumpulan->tugas_id);
        if ( ! $tugas) show_error('Tugas tidak ditemukan.', 404);

        if ($tugas->jenis !== 'individu') {
            $this->session->set_flashdata('error', 'Permintaan revisi hanya berlaku untuk tugas individu. Tugas kelompok menggunakan pengaturan allow revisi.');
            redirect('guru/detail_tugas/' . $tugas->id);
            return;
        }

        $mapel = $this->Mapel_model->get_by_id($tugas->mapel_id);
        if ( ! $mapel || $mapel->guru_id != $this->session->userdata('user_id')) {
            show_error('Anda tidak memiliki akses.', 403);
        }

        $this->form_validation->set_rules('feedback', 'Instruksi Revisi', 'trim|required');

        if ( ! $this->form_validation->run()) {
            $this->session->set_flashdata('error', validation_errors('', ''));
            redirect('guru/detail_tugas/' . $tugas->id);
            return;
        }

        $this->Tugas_model->minta_revisi($pengumpulan_id, $this->input->post('feedback'));
        $this->session->set_flashdata('success', 'Permintaan revisi berhasil dikirim.');
        redirect('guru/detail_tugas/' . $tugas->id);
    }

    public function hapus_pengumpulan($id)
    {
        $pengumpulan = $this->Tugas_model->get_pengumpulan_by_id($id);
        if ( ! $pengumpulan) show_404();

        $tugas = $this->Tugas_model->get_by_id($pengumpulan->tugas_id);
        $mapel = $this->Mapel_model->get_by_id($tugas->mapel_id);
        if ($mapel->guru_id != $this->session->userdata('user_id')) show_error('Akses ditolak.', 403);

        $this->Tugas_model->delete_pengumpulan($id);
        $this->session->set_flashdata('success', 'Pengumpulan berhasil dihapus.');
        redirect('guru/detail_tugas/' . $tugas->id);
    }

    public function bulk_hapus_pengumpulan($tugas_id)
    {
        if ($this->input->method() !== 'post') redirect('guru/detail_tugas/' . $tugas_id);

        $tugas = $this->Tugas_model->get_by_id($tugas_id);
        if ( ! $tugas) show_404();
        
        $mapel = $this->Mapel_model->get_by_id($tugas->mapel_id);
        if ($mapel->guru_id != $this->session->userdata('user_id')) show_error('Akses ditolak.', 403);

        $ids = $this->input->post('pengumpulan_ids');
        if (empty($ids)) {
            $this->session->set_flashdata('warning', 'Tidak ada pengumpulan yang dipilih.');
            redirect('guru/detail_tugas/' . $tugas_id);
        }

        $count = 0;
        foreach ($ids as $id) {
            $p = $this->Tugas_model->get_pengumpulan_by_id($id);
            if ($p && $p->tugas_id == $tugas_id) {
                if ($this->Tugas_model->delete_pengumpulan($id)) {
                    $count++;
                }
            }
        }

        $this->session->set_flashdata('success', $count . ' pengumpulan berhasil dihapus.');
        redirect('guru/detail_tugas/' . $tugas_id);
    }

    // =========================================================================
    //  KELOMPOK
    // =========================================================================

    public function kelola_kelompok($tugas_id)
    {
        list($tugas, $mapel) = $this->_require_kelompok_tugas($tugas_id);

        $groups         = $this->Kelompok_model->get_with_members($tugas_id);
        $unassigned     = $this->Kelompok_model->get_unassigned_students($tugas_id, $tugas->mapel_id);
        $has_submission = $this->Tugas_model->count_pengumpulan_by_tugas($tugas_id) > 0;

        $data = [
            'title'          => 'Kelola Kelompok: ' . $tugas->judul,
            'tugas'          => $tugas,
            'mapel'          => $mapel,
            'groups'         => $groups,
            'unassigned'     => $unassigned,
            'has_submission' => $has_submission,
        ];

        $data['content_view'] = $this->load->view('guru/tugas/kelola_kelompok', $data, TRUE);
        $this->load->view('guru/layout', $data);
    }

    public function acak_ulang_kelompok($tugas_id)
    {
        if ($this->input->method() !== 'post') redirect('guru/detail_tugas/' . $tugas_id);

        list($tugas) = $this->_require_kelompok_tugas($tugas_id);

        if ($this->Tugas_model->count_pengumpulan_by_tugas($tugas_id) > 0) {
            $this->session->set_flashdata('error', 'Kelompok tidak dapat diacak ulang karena sudah ada pengumpulan.');
            redirect('guru/kelola_kelompok/' . $tugas_id);
        }

        $groups = $this->Kelompok_model->get_by_tugas($tugas_id);
        $jumlah = count($groups);

        if ($jumlah < 1) {
            $total_siswa = (int) $this->Mapel_model->get_with_siswa_count($tugas->mapel_id)->jumlah_siswa;
            $per         = max(1, (int) $tugas->anggota_per_kelompok);
            $jumlah      = max(1, (int) ceil($total_siswa / $per));
        }

        $per = max(1, (int) $tugas->anggota_per_kelompok);
        $this->Kelompok_model->assign_random($tugas_id, $tugas->mapel_id, $jumlah, $per);

        $this->session->set_flashdata('success', 'Kelompok berhasil diacak ulang.');
        redirect('guru/kelola_kelompok/' . $tugas_id);
    }

    public function tambah_kelompok($tugas_id)
    {
        if ($this->input->method() !== 'post') redirect('guru/detail_tugas/' . $tugas_id);

        list($tugas) = $this->_require_kelompok_tugas($tugas_id);

        $groups = $this->Kelompok_model->get_by_tugas($tugas_id);
        $nama   = 'Kelompok ' . (count($groups) + 1);

        $this->Kelompok_model->add_group($tugas_id, $nama);
        $this->session->set_flashdata('success', 'Kelompok baru berhasil ditambahkan.');
        redirect('guru/kelola_kelompok/' . $tugas_id);
    }

    public function hapus_kelompok($kelompok_id)
    {
        list($group, $tugas) = $this->_require_kelompok_group($kelompok_id);

        if ($this->Tugas_model->get_pengumpulan_by_kelompok($tugas->id, $group->id)) {
            $this->session->set_flashdata('error', 'Kelompok tidak dapat dihapus karena sudah memiliki pengumpulan.');
            redirect('guru/kelola_kelompok/' . $tugas->id);
        }

        $this->Kelompok_model->delete_group($group->id);
        $this->session->set_flashdata('success', 'Kelompok berhasil dihapus.');
        redirect('guru/kelola_kelompok/' . $tugas->id);
    }

    public function tambah_anggota_kelompok($kelompok_id)
    {
        if ($this->input->method() !== 'post') redirect('guru/tugas');

        list($group, $tugas) = $this->_require_kelompok_group($kelompok_id);
        $siswa_id = (int) $this->input->post('siswa_id');

        $in_mapel = $this->db
            ->where('mapel_id', $tugas->mapel_id)
            ->where('siswa_id', $siswa_id)
            ->count_all_results('mapel_siswa') > 0;

        if ( ! $in_mapel) {
            $this->session->set_flashdata('error', 'Siswa tidak terdaftar di mapel ini.');
        } elseif ($this->Kelompok_model->is_in_any_group($tugas->id, $siswa_id)) {
            $this->session->set_flashdata('warning', 'Siswa sudah tergabung dalam kelompok lain.');
        } elseif ($tugas->anggota_per_kelompok && $this->Kelompok_model->count_anggota($group->id) >= (int) $tugas->anggota_per_kelompok) {
            $this->session->set_flashdata('warning', 'Kelompok sudah mencapai batas anggota.');
        } else {
            $this->Kelompok_model->add_anggota($group->id, $siswa_id);
            $this->session->set_flashdata('success', 'Anggota berhasil ditambahkan.');
        }

        redirect('guru/kelola_kelompok/' . $tugas->id);
    }

    public function hapus_anggota_kelompok($kelompok_id, $siswa_id)
    {
        list($group, $tugas) = $this->_require_kelompok_group($kelompok_id);

        if ($this->Tugas_model->get_pengumpulan_by_kelompok($tugas->id, $group->id)) {
            $this->session->set_flashdata('error', 'Anggota tidak dapat dikeluarkan karena kelompok sudah memiliki pengumpulan.');
            redirect('guru/kelola_kelompok/' . $tugas->id);
        }

        $this->Kelompok_model->remove_anggota($group->id, $siswa_id);
        $this->session->set_flashdata('success', 'Anggota berhasil dikeluarkan dari kelompok.');
        redirect('guru/kelola_kelompok/' . $tugas->id);
    }

    public function kunci_kelompok($tugas_id)
    {
        if ($this->input->method() !== 'post') redirect('guru/detail_tugas/' . $tugas_id);

        list($tugas) = $this->_require_kelompok_tugas($tugas_id);

        $locked = $tugas->kelompok_locked ? 0 : 1;
        $this->Tugas_model->update($tugas_id, ['kelompok_locked' => $locked]);

        $this->session->set_flashdata('success', $locked ? 'Pembagian kelompok dikunci.' : 'Pembagian kelompok dibuka.');
        redirect('guru/kelola_kelompok/' . $tugas_id);
    }

    private function _require_kelompok_tugas($tugas_id)
    {
        $tugas = $this->Tugas_model->get_by_id($tugas_id);
        if ( ! $tugas) show_404();

        $mapel = $this->Mapel_model->get_by_id($tugas->mapel_id);
        if ( ! $mapel || $mapel->guru_id != $this->session->userdata('user_id')) {
            show_error('Anda tidak memiliki akses.', 403);
        }

        if ($tugas->jenis !== 'kelompok') {
            show_error('Tugas ini bukan tugas kelompok.', 400);
        }

        return array($tugas, $mapel);
    }

    private function _require_kelompok_group($kelompok_id)
    {
        $group = $this->Kelompok_model->get_by_id($kelompok_id);
        if ( ! $group) show_404();

        list($tugas) = $this->_require_kelompok_tugas($group->tugas_id);

        return array($group, $tugas);
    }

    private function _assign_acak_groups($mapel_id, $siswa_id)
    {
        $this->Kelompok_model->auto_assign_for_mapel($mapel_id, $siswa_id);
    }

    private function _collect_items_from_post()
    {
        $ids        = $this->input->post('item_id');
        $labels     = $this->input->post('item_label');
        $instruksi  = $this->input->post('item_instruksi');

        if ( ! is_array($labels)) {
            return array();
        }

        $items = array();
        foreach ($labels as $i => $label) {
            $items[] = array(
                'id'        => isset($ids[$i]) ? (int) $ids[$i] : 0,
                'label'     => (string) $label,
                'instruksi' => isset($instruksi[$i]) ? (string) $instruksi[$i] : '',
            );
        }

        return $items;
    }

    private function _items_seed_from_post()
    {
        $items = array();
        foreach ($this->_collect_items_from_post() as $item) {
            if (trim($item['label']) === '' && trim($item['instruksi']) === '') {
                continue;
            }
            $items[] = (object) $item;
        }
        return $items;
    }

    // =========================================================================
    //  NOTIFIKASI
    // =========================================================================

    public function notifikasi()
    {
        $guru_id     = $this->session->userdata('user_id');
        $notif_list  = $this->Notifikasi_model->get_by_user($guru_id);

        $this->Notifikasi_model->mark_all_read($guru_id);
        $this->load->vars(['notif_unread' => 0]);

        $data = [
            'title'      => 'Notifikasi',
            'notif_list' => $notif_list,
        ];

        $data['content_view'] = $this->load->view('guru/notifikasi', $data, TRUE);
        $this->load->view('guru/layout', $data);
    }

    // =========================================================================
    //  PROFIL
    // =========================================================================

    public function profil()
    {
        $guru_id = $this->session->userdata('user_id');
        $guru    = $this->db->where('id', $guru_id)->get('users')->row();

        if ($this->input->method() === 'post') {
            $this->form_validation->set_rules('nama', 'Nama', 'required|max_length[100]');
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email|max_length[100]');

            if ($this->form_validation->run()) {
                $update_data = [
                    'nama'       => $this->input->post('nama'),
                    'email'      => $this->input->post('email'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ];

                // Handle foto upload
                if ( ! empty($_FILES['foto']['name'])) {
                    $upload_path = FCPATH . 'uploads/foto/';
                    if ( ! is_dir($upload_path)) mkdir($upload_path, 0755, TRUE);

                    $this->upload->initialize([
                        'upload_path'   => $upload_path,
                        'allowed_types' => 'jpg|jpeg|png|webp',
                        'max_size'      => 2048,
                        'max_width'     => 2000,
                        'max_height'    => 2000,
                        'encrypt_name'  => TRUE,
                    ]);

                    if ($this->upload->do_upload('foto')) {
                        if ($guru->foto) {
                            @unlink($upload_path . $guru->foto);
                        }
                        $update_data['foto'] = $this->upload->data('file_name');
                    } else {
                        $this->session->set_flashdata('error', $this->upload->display_errors('', ''));
                        redirect('guru/profil');
                        return;
                    }
                }

                // Handle password change
                $password_baru = $this->input->post('password_baru');
                if ( ! empty($password_baru)) {
                    $this->form_validation->reset_validation();
                    $this->form_validation->set_rules('password_lama', 'Password Lama', 'required');
                    $this->form_validation->set_rules('password_baru', 'Password Baru', 'required|min_length[6]');
                    $this->form_validation->set_rules('konfirmasi_password', 'Konfirmasi Password', 'required|matches[password_baru]');

                    if ($this->form_validation->run()) {
                        $password_lama = md5($this->input->post('password_lama'));
                        if ($password_lama !== $guru->password) {
                            $this->session->set_flashdata('error', 'Password lama tidak sesuai.');
                            redirect('guru/profil');
                            return;
                        }
                        $update_data['password'] = md5($password_baru);
                    } else {
                        $this->session->set_flashdata('error', validation_errors('', ''));
                        redirect('guru/profil');
                        return;
                    }
                }

                $this->db->where('id', $guru_id)->update('users', $update_data);

                // Refresh session data
                $updated_guru = $this->db->where('id', $guru_id)->get('users')->row();
                $this->session->set_userdata([
                    'nama'  => $updated_guru->nama,
                    'email' => $updated_guru->email,
                    'foto'  => $updated_guru->foto,
                ]);

                $this->session->set_flashdata('success', 'Profil berhasil diperbarui.');
                redirect('guru/profil');
            }
        }

        $data = ['title' => 'Profil Saya', 'guru' => $guru];
        $data['content_view'] = $this->load->view('guru/profil', $data, TRUE);
        $this->load->view('guru/layout', $data);
    }
}
