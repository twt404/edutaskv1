<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Siswa extends Siswa_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model(['Siswa_model', 'Tugas_model', 'Kelompok_model', 'Kelas_model', 'Mapel_model', 'Notifikasi_model']);
        $this->load->library(['form_validation', 'upload', 'session']);
        $this->load->helper(['url', 'form', 'file', 'text']);
    }

    // =========================================================================
    //  DASHBOARD
    // =========================================================================
    public function dashboard()
    {
        $siswa_id = $this->session->userdata('user_id');

        $kelas              = $this->Siswa_model->get_kelas_by_siswa($siswa_id);
        $mapel_list         = $this->Siswa_model->get_mapel_by_siswa($siswa_id);
        $upcoming_tugas     = $this->Siswa_model->get_upcoming_tugas($siswa_id, 5);
        $materi_terbaru     = $this->Siswa_model->get_materi_terbaru($siswa_id, 3);
        $recent_pengumpulan = $this->Siswa_model->get_recent_pengumpulan($siswa_id, 5);

        $stats = [
            'mapel_count'   => count($mapel_list),
            'tugas_aktif'   => $this->Siswa_model->count_tugas_aktif($siswa_id),
            'tugas_selesai' => $this->Siswa_model->count_tugas_selesai($siswa_id),
        ];

        $data = [
            'title'              => 'Dashboard Siswa',
            'kelas'              => $kelas,
            'mapel_list'         => $mapel_list,
            'upcoming_tugas'     => $upcoming_tugas,
            'materi_terbaru'     => $materi_terbaru,
            'recent_pengumpulan' => $recent_pengumpulan,
            'stats'              => $stats,
            'user'               => $this->session->userdata(),
        ];

        $data['content_view'] = $this->load->view('siswa/dashboard', $data, TRUE);
        $this->load->view('siswa/layout', $data);
    }

    // =========================================================================
    //  KELAS SAYA
    // =========================================================================
    public function kelas()
    {
        $siswa_id   = $this->session->userdata('user_id');
        $kelas      = $this->Siswa_model->get_kelas_by_siswa($siswa_id);
        $mapel_list = $this->Siswa_model->get_mapel_by_siswa($siswa_id);

        $data = [
            'title'      => 'Kelas Saya',
            'kelas'      => $kelas,
            'mapel_list' => $mapel_list,
        ];

        $data['content_view'] = $this->load->view('siswa/kelas/index', $data, TRUE);
        $this->load->view('siswa/layout', $data);
    }

    public function gabung_rombel()
    {
        redirect('siswa/gabung_kelas');
    }

    public function gabung_kelas()
    {
        $siswa_id = $this->session->userdata('user_id');

        if ($this->input->method() === 'post') {
            $this->form_validation->set_rules('kode_kelas', 'Kode Kelas', 'required|min_length[4]|max_length[20]');

            if ($this->form_validation->run()) {
                $kode  = strtoupper(trim($this->input->post('kode_kelas')));
                $kelas = $this->Kelas_model->get_by_kode($kode);

                if ( ! $kelas) {
                    $this->session->set_flashdata('error', 'Kode kelas tidak ditemukan. Pastikan kode sudah benar.');
                } elseif ($this->Kelas_model->get_kelas_of_siswa($siswa_id)) {
                    $current = $this->Kelas_model->get_kelas_of_siswa($siswa_id);
                    $this->session->set_flashdata('error', 'Anda sudah terdaftar di kelas ' . $current->nama_kelas . '. Hubungi Admin untuk pindah kelas.');
                } else {
                    $this->db->trans_start();
                    $this->Kelas_model->add_siswa($kelas->id, $siswa_id);
                    $this->Kelas_model->sync_mapel_siswa($kelas->id);
                    $this->Kelompok_model->auto_assign_for_kelas($kelas->id, $siswa_id);
                    $this->db->trans_complete();

                    if ($this->db->trans_status() === FALSE) {
                        $this->session->set_flashdata('error', 'Gagal bergabung ke kelas. Silakan coba lagi.');
                    } else {
                        $this->session->set_flashdata('success', 'Berhasil bergabung ke kelas ' . $kelas->nama_kelas . '.');
                        redirect('siswa/kelas');
                    }
                }
            } else {
                $this->session->set_flashdata('error', validation_errors('', ''));
            }

            redirect('siswa/gabung_kelas');
        }

        $data = [
            'title' => 'Gabung Kelas',
            'kelas' => $this->Kelas_model->get_kelas_of_siswa($siswa_id),
        ];
        $data['content_view'] = $this->load->view('siswa/kelas/gabung', $data, TRUE);
        $this->load->view('siswa/layout', $data);
    }

    private function _assign_acak_groups($mapel_id, $siswa_id)
    {
        $this->Kelompok_model->auto_assign_for_mapel($mapel_id, $siswa_id);
    }

    public function detail_mapel($id)
    {
        $siswa_id = $this->session->userdata('user_id');

        // Check if enrolled
        if ( ! $this->Siswa_model->is_enrolled_mapel($siswa_id, $id)) {
            show_error('Anda tidak terdaftar di mapel ini atau tidak memiliki akses.', 403);
        }

        $mapel = $this->Siswa_model->get_mapel_detail($id);
        if ( ! $mapel) show_404();

        $materi_list = $this->Siswa_model->get_materi_by_mapel($id);
        $tugas_list  = $this->Siswa_model->get_tugas_by_mapel_with_status($id, $siswa_id);

        $data = [
            'title'       => 'Detail Mapel: ' . $mapel->nama_mapel,
            'mapel'       => $mapel,
            'materi_list' => $materi_list,
            'tugas_list'  => $tugas_list,
        ];

        $data['content_view'] = $this->load->view('siswa/mapel/detail', $data, TRUE);
        $this->load->view('siswa/layout', $data);
    }

    // =========================================================================
    //  MATERI
    // =========================================================================
    public function materi($id)
    {
        $siswa_id = $this->session->userdata('user_id');
        $materi   = $this->Siswa_model->get_materi($id);
        if ( ! $materi) show_404();

        if ( ! $this->Siswa_model->is_enrolled_mapel($siswa_id, $materi->mapel_id)) {
            show_error('Anda tidak terdaftar di mapel untuk materi ini.', 403);
        }

        $data = [
            'title'  => 'Materi: ' . $materi->judul,
            'materi' => $materi,
        ];

        $data['content_view'] = $this->load->view('siswa/materi/detail', $data, TRUE);
        $this->load->view('siswa/layout', $data);
    }

    // =========================================================================
    //  TUGAS
    // =========================================================================
    public function tugas()
    {
        $siswa_id   = $this->session->userdata('user_id');
        $filter     = $this->input->get('filter') ? $this->input->get('filter') : 'semua';
        $tugas_list = $this->Siswa_model->get_all_tugas_for_siswa($siswa_id, $filter);

        $data = [
            'title'      => 'Daftar Tugas',
            'tugas_list' => $tugas_list,
            'filter'     => $filter,
        ];

        $data['content_view'] = $this->load->view('siswa/tugas/index', $data, TRUE);
        $this->load->view('siswa/layout', $data);
    }

    public function detail_tugas($id)
    {
        $siswa_id = $this->session->userdata('user_id');
        $tugas    = $this->Siswa_model->get_tugas_detail($id);
        if ( ! $tugas) show_404();

        if ( ! $this->Siswa_model->is_enrolled_mapel($siswa_id, $tugas->mapel_id)) {
            show_error('Anda tidak terdaftar di mapel tugas ini.', 403);
        }

        if ( ! $this->Siswa_model->is_penerima_tugas($id, $siswa_id)) {
            show_error('Tugas ini tidak ditugaskan untuk Anda.', 403);
        }

        $kelompok         = NULL;
        $kelompok_members = array();
        $available_groups = array();

        if ($tugas->jenis === 'kelompok') {
            $kelompok = $this->Kelompok_model->get_group_of_siswa($id, $siswa_id);

            if ( ! $kelompok && $tugas->mode_kelompok === 'acak' && ! $tugas->kelompok_locked) {
                $this->Kelompok_model->assign_late_student($id, $siswa_id);
                $kelompok = $this->Kelompok_model->get_group_of_siswa($id, $siswa_id);
            }

            $pengumpulan = $kelompok ? $this->Tugas_model->get_pengumpulan_by_kelompok($id, $kelompok->id) : NULL;

            if ($kelompok) {
                $kelompok_members = $this->db
                    ->select('u.id, u.nama, u.email, u.foto')
                    ->from('kelompok_anggota ka')
                    ->join('users u', 'u.id = ka.siswa_id')
                    ->where('ka.kelompok_id', $kelompok->id)
                    ->order_by('u.nama', 'ASC')
                    ->get()
                    ->result();
            } elseif ($tugas->mode_kelompok === 'siswa' && ! $tugas->kelompok_locked) {
                $available_groups = $this->Kelompok_model->get_by_tugas($id);
            }
        } else {
            $pengumpulan = $this->Siswa_model->get_pengumpulan($id, $siswa_id);
        }

        $is_overdue = (strtotime($tugas->deadline) < time());
        $is_revision_requested = $pengumpulan && $pengumpulan->status === 'revisi';
        $can_revise = $is_revision_requested
            || ($tugas->jenis === 'kelompok' && (int) $tugas->allow_revisi === 1 && ! $is_overdue);

        $can_edit_items = TRUE;
        if ($pengumpulan && $pengumpulan->status === 'dinilai') {
            $can_edit_items = $is_revision_requested
                || ($tugas->jenis === 'kelompok' && (int) $tugas->allow_revisi === 1 && ! $is_overdue);
        }

        $tugas_items = $this->Tugas_model->get_items($id);
        $item_files  = array();
        $legacy_files = array();
        if ($pengumpulan) {
            foreach ($this->Tugas_model->get_files_by_pengumpulan($pengumpulan->id) as $file) {
                if ($file->tugas_item_id) {
                    $item_files[(int) $file->tugas_item_id][] = $file;
                } else {
                    $legacy_files[] = $file;
                }
            }
        }

        $data = [
            'title'            => 'Detail Tugas: ' . $tugas->judul,
            'tugas'            => $tugas,
            'pengumpulan'      => $pengumpulan,
            'is_overdue'       => $is_overdue,
            'is_revision_requested' => $is_revision_requested,
            'can_revise'       => $can_revise,
            'can_edit_items'   => $can_edit_items,
            'kelompok'         => $kelompok,
            'kelompok_members' => $kelompok_members,
            'available_groups' => $available_groups,
            'tugas_items'      => $tugas_items,
            'item_files'       => $item_files,
            'legacy_files'     => $legacy_files,
            'lampiran'         => $this->Tugas_model->get_lampiran($id),
        ];

        $data['content_view'] = $this->load->view('siswa/tugas/detail', $data, TRUE);
        $this->load->view('siswa/layout', $data);
    }

    public function kumpul_tugas($id)
    {
        if ($this->input->method() !== 'post') {
            redirect('siswa/detail_tugas/' . $id);
        }

        $siswa_id = $this->session->userdata('user_id');
        $tugas    = $this->Siswa_model->get_tugas_detail($id);
        if ( ! $tugas) show_404();

        if ( ! $this->Siswa_model->is_enrolled_mapel($siswa_id, $tugas->mapel_id)) {
            show_error('Akses ditolak.', 403);
        }

        if ( ! $this->Siswa_model->is_penerima_tugas($id, $siswa_id)) {
            show_error('Tugas ini tidak ditugaskan untuk Anda.', 403);
        }

        $kelompok = NULL;

        if ($tugas->jenis === 'kelompok') {
            $kelompok = $this->Kelompok_model->get_group_of_siswa($id, $siswa_id);
            if ( ! $kelompok) {
                $this->session->set_flashdata('error', 'Anda belum tergabung dalam kelompok untuk tugas ini.');
                redirect('siswa/detail_tugas/' . $id);
                return;
            }
            $existing = $this->Tugas_model->get_pengumpulan_by_kelompok($id, $kelompok->id);
        } else {
            $existing = $this->Siswa_model->get_pengumpulan($id, $siswa_id);
        }

        $is_revision_requested = $existing && $existing->status === 'revisi';
        $allow_partial_update = $existing && in_array($existing->status, array('menunggu', 'terlambat'), TRUE);
        $allow_revision = $is_revision_requested
            || $allow_partial_update
            || ($tugas->jenis === 'kelompok' && (int) $tugas->allow_revisi === 1);

        if ($existing && ! $allow_revision) {
            $this->session->set_flashdata('error', 'Tugas sudah dikumpulkan sebelumnya.');
            redirect('siswa/detail_tugas/' . $id);
            return;
        }

        $is_overdue = (strtotime($tugas->deadline) < time());
        $status     = ($is_revision_requested || ! $is_overdue) ? 'menunggu' : 'terlambat';

        $tugas_items = $this->Tugas_model->get_items($id);
        $upload_path = FCPATH . 'uploads/jawaban/';
        if ( ! is_dir($upload_path)) {
            mkdir($upload_path, 0755, TRUE);
        }

        $existing_files = array();
        $legacy_existing = array();
        if ($existing) {
            foreach ($this->Tugas_model->get_files_by_pengumpulan($existing->id) as $file) {
                if ($file->tugas_item_id) {
                    $existing_files[(int) $file->tugas_item_id][] = $file;
                } else {
                    $legacy_existing[] = $file;
                }
            }
        }

        $uploaded = array();
        $legacy_uploaded = array();
        $upload_errors = array();

        if ( ! empty($tugas_items)) {
            foreach ($tugas_items as $item) {
                $field = 'item_files_' . (int) $item->id;
                $files = $this->_upload_multiple($field, $upload_path, $upload_errors);
                if ( ! empty($files)) {
                    $uploaded[(int) $item->id] = $files;
                }
            }
        } else {
            $legacy_uploaded = $this->_upload_multiple('file_jawaban', $upload_path, $upload_errors);
        }

        if ( ! empty($upload_errors)) {
            foreach ($uploaded as $files) {
                $this->_unlink_uploaded($upload_path, $files);
            }
            $this->_unlink_uploaded($upload_path, $legacy_uploaded);
            $this->session->set_flashdata('error', implode(' | ', $upload_errors));
            redirect('siswa/detail_tugas/' . $id);
            return;
        }

        $file_jawaban = $existing ? $existing->file_jawaban : '';

        if ( ! empty($legacy_uploaded)) {
            $file_jawaban = $legacy_uploaded[0]['nama_file'];
        } elseif ( ! empty($uploaded)) {
            $first_item_files = reset($uploaded);
            $file_jawaban = $first_item_files[0]['nama_file'];
        } elseif ($file_jawaban === '' && ! empty($existing_files)) {
            $first_item_files = reset($existing_files);
            $file_jawaban = $first_item_files[0]->nama_file;
        } elseif ($file_jawaban === '' && ! empty($legacy_existing)) {
            $file_jawaban = $legacy_existing[0]->nama_file;
        }

        $catatan = trim((string) $this->input->post('catatan'));
        $has_files = ! empty($uploaded) || ! empty($legacy_uploaded) || ! empty($existing_files) || ! empty($legacy_existing) || $file_jawaban !== '';
        if ( ! $has_files && $catatan === '') {
            $this->session->set_flashdata('error', 'Unggah minimal satu berkas atau isi catatan.');
            redirect('siswa/detail_tugas/' . $id);
            return;
        }

        $pengumpulan_data = [
            'tugas_id'     => $id,
            'siswa_id'     => $siswa_id,
            'kelompok_id'  => $kelompok ? $kelompok->id : NULL,
            'file_jawaban' => $file_jawaban,
            'catatan'      => $catatan !== '' ? $catatan : NULL,
            'status'       => $status,
            'submitted_at' => date('Y-m-d H:i:s'),
        ];

        if ($existing) {
            $pengumpulan_id = $existing->id;

            if ($existing->nilai !== NULL) {
                $pengumpulan_data['nilai_sebelumnya']      = $existing->nilai;
                $pengumpulan_data['feedback_sebelumnya']   = $existing->feedback;
                $pengumpulan_data['dinilai_at_sebelumnya'] = $existing->dinilai_at;
            }

            $pengumpulan_data['nilai']      = NULL;
            $pengumpulan_data['dinilai_at'] = NULL;

            if ( ! $is_revision_requested) {
                $pengumpulan_data['feedback'] = NULL;
            }

            $this->Siswa_model->update_pengumpulan($pengumpulan_id, $pengumpulan_data);
        } else {
            $pengumpulan_id = $this->Siswa_model->insert_pengumpulan($pengumpulan_data);
        }

        foreach ($uploaded as $item_id => $files) {
            $old_files = $this->Tugas_model->get_files_by_pengumpulan_item($pengumpulan_id, $item_id);
            foreach ($files as $file) {
                $this->Tugas_model->add_file($pengumpulan_id, $item_id, $file['nama_file'], $file['nama_asli'], $file['ukuran']);
            }
            foreach ($old_files as $old_file) {
                $this->Tugas_model->delete_file_by_id($old_file->id);
            }
        }

        if ( ! empty($legacy_uploaded)) {
            $old_files = $this->Tugas_model->get_files_without_item($pengumpulan_id);
            foreach ($legacy_uploaded as $file) {
                $this->Tugas_model->add_file($pengumpulan_id, NULL, $file['nama_file'], $file['nama_asli'], $file['ukuran']);
            }
            foreach ($old_files as $old_file) {
                $this->Tugas_model->delete_file_by_id($old_file->id);
            }
            if ($existing && ! empty($existing->file_jawaban)) {
                @unlink($upload_path . $existing->file_jawaban);
            }
        }

        $this->_notify_guru_pengumpulan($tugas, $pengumpulan_id, $kelompok, NULL, $catatan);

        if ($is_revision_requested) {
            $msg = 'Revisi tugas berhasil dikirim!';
        } else {
            $msg = ($status === 'terlambat') ? 'Tugas berhasil dikumpulkan (terlambat).' : 'Tugas berhasil dikumpulkan!';
        }
        $this->session->set_flashdata('success', $msg);
        redirect('siswa/detail_tugas/' . $id);
    }

    public function simpan_item($tugas_id, $item_id)
    {
        if ($this->input->method() !== 'post') {
            redirect('siswa/detail_tugas/' . $tugas_id);
        }

        $siswa_id = (int) $this->session->userdata('user_id');
        $tugas    = $this->Siswa_model->get_tugas_detail($tugas_id);
        if ( ! $tugas) show_404();

        if ( ! $this->Siswa_model->is_enrolled_mapel($siswa_id, $tugas->mapel_id)) {
            show_error('Akses ditolak.', 403);
        }

        if ( ! $this->Siswa_model->is_penerima_tugas($tugas_id, $siswa_id)) {
            show_error('Tugas ini tidak ditugaskan untuk Anda.', 403);
        }

        $item = $this->db
            ->where('id', (int) $item_id)
            ->where('tugas_id', (int) $tugas_id)
            ->get('tugas_item')
            ->row();
        if ( ! $item) show_error('Poin tugas tidak ditemukan.', 404);

        $kelompok = NULL;

        if ($tugas->jenis === 'kelompok') {
            $kelompok = $this->Kelompok_model->get_group_of_siswa($tugas_id, $siswa_id);
            if ( ! $kelompok) {
                $this->session->set_flashdata('error', 'Anda belum tergabung dalam kelompok untuk tugas ini.');
                redirect('siswa/detail_tugas/' . $tugas_id);
                return;
            }
            $existing = $this->Tugas_model->get_pengumpulan_by_kelompok($tugas_id, $kelompok->id);
        } else {
            $existing = $this->Siswa_model->get_pengumpulan($tugas_id, $siswa_id);
        }

        $is_overdue             = (strtotime($tugas->deadline) < time());
        $is_revision_requested  = $existing && $existing->status === 'revisi';
        $group_revision         = $tugas->jenis === 'kelompok'
            && (int) $tugas->allow_revisi === 1
            && ! $is_overdue;
        $is_locked              = $existing
            && $existing->status === 'dinilai'
            && ! $is_revision_requested
            && ! $group_revision;

        if ($is_locked) {
            $this->session->set_flashdata('error', 'Tugas sudah dinilai dan tidak dapat diubah.');
            redirect('siswa/detail_tugas/' . $tugas_id);
            return;
        }

        $upload_path = FCPATH . 'uploads/jawaban/';
        if ( ! is_dir($upload_path)) {
            mkdir($upload_path, 0755, TRUE);
        }

        $upload_errors = array();
        $files = $this->_upload_multiple('item_files_' . (int) $item->id, $upload_path, $upload_errors);

        if ( ! empty($upload_errors)) {
            $this->_unlink_uploaded($upload_path, $files);
            $this->session->set_flashdata('error', implode(' | ', $upload_errors));
            redirect('siswa/detail_tugas/' . $tugas_id);
            return;
        }

        if (empty($files)) {
            $this->session->set_flashdata('error', 'Pilih minimal satu berkas untuk poin ini.');
            redirect('siswa/detail_tugas/' . $tugas_id);
            return;
        }

        $status = ($is_revision_requested || ! $is_overdue) ? 'menunggu' : 'terlambat';

        $pengumpulan_data = array(
            'tugas_id'     => $tugas_id,
            'siswa_id'     => $siswa_id,
            'kelompok_id'  => $kelompok ? $kelompok->id : NULL,
            'file_jawaban' => $files[0]['nama_file'],
            'status'       => $status,
            'submitted_at' => date('Y-m-d H:i:s'),
        );

        if ($existing) {
            if ($existing->nilai !== NULL) {
                $pengumpulan_data['nilai_sebelumnya']      = $existing->nilai;
                $pengumpulan_data['feedback_sebelumnya']   = $existing->feedback;
                $pengumpulan_data['dinilai_at_sebelumnya'] = $existing->dinilai_at;
            }

            $pengumpulan_data['nilai']      = NULL;
            $pengumpulan_data['dinilai_at'] = NULL;

            if ( ! $is_revision_requested) {
                $pengumpulan_data['feedback'] = NULL;
            }

            $this->Siswa_model->update_pengumpulan($existing->id, $pengumpulan_data);
            $pengumpulan_id = $existing->id;
        } else {
            $pengumpulan_id = $this->Siswa_model->insert_pengumpulan($pengumpulan_data);
        }

        if ( ! $pengumpulan_id) {
            $this->_unlink_uploaded($upload_path, $files);
            $this->session->set_flashdata('error', 'Gagal menyimpan pengumpulan. Silakan coba lagi.');
            redirect('siswa/detail_tugas/' . $tugas_id);
            return;
        }

        $old_files = $this->Tugas_model->get_files_by_pengumpulan_item($pengumpulan_id, $item->id);

        foreach ($files as $file) {
            $this->Tugas_model->add_file(
                $pengumpulan_id,
                $item->id,
                $file['nama_file'],
                $file['nama_asli'],
                $file['ukuran']
            );
        }

        foreach ($old_files as $old_file) {
            $this->Tugas_model->delete_file_by_id($old_file->id);
        }

        $fresh = $this->Tugas_model->get_pengumpulan_by_id($pengumpulan_id);
        $this->_notify_guru_pengumpulan($tugas, $pengumpulan_id, $kelompok, $item, $fresh ? $fresh->catatan : '');

        $this->session->set_flashdata('success', 'Berkas untuk poin "' . $item->label . '" berhasil disimpan.');
        redirect('siswa/detail_tugas/' . $tugas_id);
    }

    private function _notify_guru_pengumpulan($tugas, $pengumpulan_id, $kelompok = NULL, $item = NULL, $catatan = '')
    {
        if ( ! $tugas || empty($tugas->mapel_id) || empty($pengumpulan_id)) {
            return;
        }

        $mapel = $this->Mapel_model->get_by_id($tugas->mapel_id);
        if ( ! $mapel || empty($mapel->guru_id)) {
            return;
        }

        $nama_siswa = (string) $this->session->userdata('nama');
        $pelaku     = $kelompok ? 'Kelompok ' . $kelompok->nama_kelompok : ($nama_siswa !== '' ? $nama_siswa : 'Siswa');

        $lines = array($pelaku . ' mengumpulkan tugas "' . $tugas->judul . '".');

        if ($item && ! empty($item->label)) {
            $lines[] = 'Poin: ' . $item->label . '.';
        }

        if ($kelompok && $nama_siswa !== '') {
            $lines[] = 'Dikirim oleh: ' . $nama_siswa . '.';
        }

        $catatan = trim((string) $catatan);
        if ($catatan !== '') {
            $lines[] = 'Catatan: ' . $catatan;
        }

        $this->Notifikasi_model->upsert_pengumpulan(
            $mapel->guru_id,
            $tugas->id,
            $pengumpulan_id,
            'Pengumpulan Tugas',
            implode("\n", $lines)
        );
    }

    private function _upload_multiple($field, $upload_path, &$errors)
    {
        $result = array();
        if (empty($_FILES[$field]['name'])) {
            return $result;
        }

        $names   = (array) $_FILES[$field]['name'];
        $types   = (array) $_FILES[$field]['type'];
        $tmps    = (array) $_FILES[$field]['tmp_name'];
        $codes   = (array) $_FILES[$field]['error'];
        $sizes   = (array) $_FILES[$field]['size'];
        $single  = $field . '_single';

        for ($i = 0; $i < count($names); $i++) {
            $original = (string) $names[$i];
            if ($original === '') {
                continue;
            }

            $code = isset($codes[$i]) ? (int) $codes[$i] : UPLOAD_ERR_NO_FILE;
            if ($code !== UPLOAD_ERR_OK) {
                $errors[] = $original . ': ' . $this->_upload_error_text($code);
                continue;
            }

            $_FILES[$single] = array(
                'name'     => $original,
                'type'     => isset($types[$i]) ? $types[$i] : '',
                'tmp_name' => isset($tmps[$i]) ? $tmps[$i] : '',
                'error'    => $code,
                'size'     => isset($sizes[$i]) ? (int) $sizes[$i] : 0,
            );

            $this->upload->initialize(array(
                'upload_path'   => $upload_path,
                'allowed_types' => '*',
                'max_size'      => 20480,
                'encrypt_name'  => TRUE,
            ));

            if ( ! $this->upload->do_upload($single)) {
                $message = trim(strip_tags($this->upload->display_errors('', '')));
                $errors[] = $original . ': ' . ($message !== '' ? $message : 'gagal diunggah.');
            } else {
                $up = $this->upload->data();
                $result[] = array(
                    'nama_file' => $up['file_name'],
                    'nama_asli' => $original,
                    'ukuran'    => isset($sizes[$i]) ? (int) $sizes[$i] : 0,
                );
            }

            unset($_FILES[$single]);
        }

        return $result;
    }

    private function _unlink_uploaded($upload_path, $files)
    {
        foreach ((array) $files as $file) {
            if (is_array($file) && ! empty($file['nama_file'])) {
                @unlink($upload_path . $file['nama_file']);
            }
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

    public function gabung_kelompok($tugas_id)
    {
        if ($this->input->method() !== 'post') {
            redirect('siswa/detail_tugas/' . $tugas_id);
        }

        $siswa_id = $this->session->userdata('user_id');
        $tugas    = $this->Siswa_model->get_tugas_detail($tugas_id);
        if ( ! $tugas) show_404();

        if ( ! $this->Siswa_model->is_enrolled_mapel($siswa_id, $tugas->mapel_id)) {
            show_error('Akses ditolak.', 403);
        }

        if ($tugas->jenis !== 'kelompok' || $tugas->mode_kelompok !== 'siswa') {
            $this->session->set_flashdata('error', 'Tugas ini tidak menggunakan mode pilih kelompok.');
            redirect('siswa/detail_tugas/' . $tugas_id);
            return;
        }

        if ($tugas->kelompok_locked) {
            $this->session->set_flashdata('error', 'Pembagian kelompok sudah dikunci oleh guru.');
            redirect('siswa/detail_tugas/' . $tugas_id);
            return;
        }

        if ($this->Kelompok_model->is_in_any_group($tugas_id, $siswa_id)) {
            $this->session->set_flashdata('warning', 'Anda sudah tergabung dalam kelompok.');
            redirect('siswa/detail_tugas/' . $tugas_id);
            return;
        }

        $kelompok_id = (int) $this->input->post('kelompok_id');

        $this->db->trans_start();

        $group = $this->db->query(
            'SELECT * FROM kelompok WHERE id = ? AND tugas_id = ? FOR UPDATE',
            array($kelompok_id, $tugas_id)
        )->row();

        $error = '';
        if ( ! $group) {
            $error = 'Kelompok tidak ditemukan.';
        } elseif ($tugas->anggota_per_kelompok && $this->Kelompok_model->count_anggota($group->id) >= (int) $tugas->anggota_per_kelompok) {
            $error = 'Kelompok sudah penuh. Silakan pilih kelompok lain.';
        } else {
            $this->Kelompok_model->add_anggota($group->id, $siswa_id);
        }

        $this->db->trans_complete();

        if ($error !== '' || $this->db->trans_status() === FALSE) {
            $this->session->set_flashdata('error', $error !== '' ? $error : 'Gagal bergabung ke kelompok.');
        } else {
            $this->session->set_flashdata('success', 'Berhasil bergabung ke kelompok.');
        }

        redirect('siswa/detail_tugas/' . $tugas_id);
    }

    public function keluar_kelompok($tugas_id)
    {
        if ($this->input->method() !== 'post') {
            redirect('siswa/detail_tugas/' . $tugas_id);
        }

        $siswa_id = $this->session->userdata('user_id');
        $tugas    = $this->Siswa_model->get_tugas_detail($tugas_id);
        if ( ! $tugas) show_404();

        if ( ! $this->Siswa_model->is_enrolled_mapel($siswa_id, $tugas->mapel_id)) {
            show_error('Akses ditolak.', 403);
        }

        if ($tugas->jenis !== 'kelompok' || $tugas->mode_kelompok !== 'siswa' || $tugas->kelompok_locked) {
            $this->session->set_flashdata('error', 'Kelompok tidak dapat diubah saat ini.');
            redirect('siswa/detail_tugas/' . $tugas_id);
            return;
        }

        $kelompok = $this->Kelompok_model->get_group_of_siswa($tugas_id, $siswa_id);
        if ( ! $kelompok) {
            $this->session->set_flashdata('warning', 'Anda belum tergabung dalam kelompok.');
            redirect('siswa/detail_tugas/' . $tugas_id);
            return;
        }

        if ($this->Tugas_model->get_pengumpulan_by_kelompok($tugas_id, $kelompok->id)) {
            $this->session->set_flashdata('error', 'Kelompok sudah mengumpulkan tugas, Anda tidak dapat keluar.');
            redirect('siswa/detail_tugas/' . $tugas_id);
            return;
        }

        $this->Kelompok_model->remove_anggota($kelompok->id, $siswa_id);
        $this->session->set_flashdata('success', 'Anda berhasil keluar dari kelompok.');
        redirect('siswa/detail_tugas/' . $tugas_id);
    }

    // =========================================================================
    //  PROFIL
    // =========================================================================
    public function profil()
    {
        $siswa_id = $this->session->userdata('user_id');
        $user     = $this->Siswa_model->get_user($siswa_id);

        if ($this->input->method() === 'post') {
            $this->form_validation->set_rules('nama', 'Nama Lengkap', 'required|max_length[100]');

            if ($this->form_validation->run()) {
                $update_data = [
                    'nama' => $this->input->post('nama'),
                ];

                // Handle Foto
                if ( ! empty($_FILES['foto']['name'])) {
                    $upload_path = FCPATH . 'uploads/foto/';
                    if ( ! is_dir($upload_path)) {
                        mkdir($upload_path, 0755, TRUE);
                    }

                    $this->upload->initialize([
                        'upload_path'   => $upload_path,
                        'allowed_types' => 'jpg|jpeg|png|gif|webp',
                        'max_size'      => 2048,
                        'file_name'     => 'foto_siswa_' . $siswa_id . '_' . time(),
                    ]);

                    if ($this->upload->do_upload('foto')) {
                        if ($user->foto) {
                            @unlink($upload_path . $user->foto);
                        }
                        $upload_data = $this->upload->data();
                        $update_data['foto'] = $upload_data['file_name'];
                    } else {
                        $this->session->set_flashdata('error', $this->upload->display_errors('', ''));
                        redirect('siswa/profil');
                        return;
                    }
                }

                // Handle Password Change
                $new_password = $this->input->post('password_baru');
                if ( ! empty($new_password)) {
                    $this->form_validation->reset_validation();
                    $this->form_validation->set_rules('password_lama', 'Password Lama', 'required');
                    $this->form_validation->set_rules('password_baru', 'Password Baru', 'required|min_length[6]');
                    $this->form_validation->set_rules('konfirmasi_password', 'Konfirmasi Password', 'required|matches[password_baru]');

                    if ($this->form_validation->run()) {
                        if (md5($this->input->post('password_lama')) !== $user->password) {
                            $this->session->set_flashdata('error', 'Password lama tidak sesuai.');
                            redirect('siswa/profil');
                            return;
                        }
                        $update_data['password'] = md5($new_password);
                    } else {
                        $this->session->set_flashdata('error', validation_errors('', ''));
                        redirect('siswa/profil');
                        return;
                    }
                }

                $this->Siswa_model->update_user($siswa_id, $update_data);
                $this->session->set_userdata('nama', $update_data['nama']);
                if (isset($update_data['foto'])) {
                    $this->session->set_userdata('foto', $update_data['foto']);
                }

                $this->session->set_flashdata('success', 'Profil berhasil diperbarui.');
                redirect('siswa/profil');
            }
        }

        $data = [
            'title'     => 'Profil Saya',
            'user_data' => $this->Siswa_model->get_user($siswa_id),
        ];

        $data['content_view'] = $this->load->view('siswa/profil', $data, TRUE);
        $this->load->view('siswa/layout', $data);
    }

    // =========================================================================
    //  NOTIFIKASI
    // =========================================================================
    public function notifikasi()
    {
        $siswa_id = $this->session->userdata('user_id');

        // Mark as read
        $this->Siswa_model->mark_notifikasi_read($siswa_id);
        $notif_list = $this->Siswa_model->get_notifikasi($siswa_id);

        $data = [
            'title'      => 'Notifikasi',
            'notif_list' => $notif_list,
        ];

        $data['content_view'] = $this->load->view('siswa/notifikasi', $data, TRUE);
        $this->load->view('siswa/layout', $data);
    }
}
