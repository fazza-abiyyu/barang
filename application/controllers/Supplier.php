<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Supplier extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        cek_login();

        $this->load->model('Admin_model', 'admin');
        $this->load->library('form_validation');
        $this->load->library('session');
        $this->load->helper('url');
    }

    public function index()
    {
        $data['title'] = "Distributor";
        $data['distributor'] = $this->admin->get('distributor');
        $this->template->load('templates/dashboard', 'supplier/data', $data);
    }

    private function _validasi()
    {
        $this->form_validation->set_rules('nama_distributor', 'Nama Distributor', 'required|trim');
        $this->form_validation->set_rules('no_telp', 'Nomor Telepon', 'required|trim|numeric');
        $this->form_validation->set_rules('alamat', 'Alamat', 'required|trim');
        $this->form_validation->set_rules('type', 'Tipe', 'required');
    }

    public function add()
    {
        $this->_validasi();

        if ($this->form_validation->run() == false) {
            $data['title'] = "Tambah Distributor";
            $this->template->load('templates/dashboard', 'supplier/add', $data);
        } else {
            // Menyimpan data distributor
            $input = $this->input->post(null, true);
            $save = $this->admin->insert('distributor', $input);
            if ($save) {
                set_pesan('Data berhasil disimpan.');
                redirect('supplier');
            } else {
                set_pesan('Data gagal disimpan.', false);
                redirect('supplier/add');
            }
        }
    }

    public function edit($getId)
    {
        $id = encode_php_tags($getId);
        $this->_validasi();

        if ($this->form_validation->run() == false) {
            $data['title'] = "Edit Distributor"; // Ubah judul
            $data['distributor'] = $this->admin->get('distributor', ['id_distributor' => $id]);
            $this->template->load('templates/dashboard', 'supplier/edit', $data);
        } else {
            // Mengupdate data distributor
            $input = $this->input->post(null, true);
            $update = $this->admin->update('distributor', 'id_distributor', $id, $input);

            if ($update) {
                set_pesan('Data berhasil diedit.');
                redirect('supplier');
            } else {
                set_pesan('Data gagal diedit.');
                redirect('supplier/edit/' . $id);
            }
        }
    }

    public function delete($getId)
    {
        $id = encode_php_tags($getId);
        if ($this->admin->delete('distributor', 'id_distributor', $id)) {
            set_pesan('Data berhasil dihapus.');
        } else {
            set_pesan('Data gagal dihapus.', false);
        }
        redirect('supplier');
    }
}
