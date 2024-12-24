<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Barangkeluar extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        cek_login();
        $this->load->model('Admin_model', 'admin');
        $this->load->library('form_validation');
        $this->load->library('session');
    }

    public function index()
    {
        $data['title'] = "Barang Keluar";
        $data['barangkeluar'] = $this->admin->getBarangKeluar();
        $this->template->load('templates/dashboard', 'barang_keluar/data', $data);
    }

    private function _validasi()
    {
        $this->form_validation->set_rules('tanggal_keluar', 'Tanggal Keluar', 'required|trim');
        $this->form_validation->set_rules('barang_id', 'Barang', 'required');
        $this->form_validation->set_rules('jumlah_keluar', 'Jumlah Keluar', 'required|trim|numeric|greater_than[0]');
        $this->form_validation->set_rules('pembeli_id', 'Pembeli', 'required');
    }
    public function add()
    {
        $this->_validasi();
        if ($this->form_validation->run() == false) {
            $data['title'] = "Tambah Barang Keluar";
            $data['barang'] = $this->admin->get('barang', null, ['stok >' => 0]);
            $data['distributor'] = $this->admin->getAllDistributors();

            $kode = 'T-BK-' . date('ymd');
            $kode_terakhir = $this->admin->getMax('barang_keluar', 'id_barang_keluar', $kode);
            $kode_tambah = substr($kode_terakhir, -5, 5);
            $kode_tambah++;
            $number = str_pad($kode_tambah, 5, '0', STR_PAD_LEFT);
            $data['id_barang_keluar'] = $kode . $number;

            $this->template->load('templates/dashboard', 'barang_keluar/add', $data);
        } else {
            $input = $this->input->post(null, true);

            $barang_id = $input['barang_id'];
            $barang = $this->admin->get('barang', ['id_barang' => $barang_id]);

            if (!$barang) {
                set_pesan('Barang tidak ditemukan!');
                redirect('barangkeluar/add');
            }

            $stok = $barang['stok'];
            $harga_satuan = $barang['harga_satuan'];
            $jumlah_keluar = $input['jumlah_keluar'];

            $stok_terbaru = $stok - $jumlah_keluar;

            if ($stok_terbaru < 0) {
                set_pesan('Stok barang tidak cukup!');
                redirect('barangkeluar/add');
            }

            $this->admin->update('barang', 'id_barang', $barang_id, ['stok' => $stok_terbaru]);

            $input['harga_satuan'] = $harga_satuan;
            $input['total_harga'] = $harga_satuan * $jumlah_keluar;

            $input['pembeli_id'] = $input['pembeli_id'];

            if (!isset($input['satuan_id'])) {
                set_pesan('Satuan ID tidak ditemukan!', false);
                redirect('barangkeluar/add');
            }
            $input['satuan_id'] = $input['satuan_id'];

            $login_session = $this->session->userdata('login_session');
            if (!isset($login_session['user'])) {
                set_pesan('User ID tidak ditemukan, harap login kembali.', false);
                redirect('auth');
            }
            $input['user_id'] = $login_session['user'];

            $input['id_barang_keluar'] = $this->input->post('id_barang_keluar', true);

            $insert = $this->admin->insert('barang_keluar', $input);

            if ($insert) {
                set_pesan('Data berhasil disimpan.');
                redirect('barangkeluar');
            } else {
                set_pesan('Oops ada kesalahan!');
                redirect('barangkeluar/add');
            }
        }
    }


    public function delete($getId = NULL)
    {
        $id = encode_php_tags($getId);
        if ($this->admin->delete('barang_keluar', 'id_barang_keluar', $id)) {
            set_pesan('Data berhasil dihapus.');
        } else {
            set_pesan('Data gagal dihapus.', false);
        }
        redirect('barangkeluar');
    }
    public function getHargaSatuan($barang_id)
    {
        $barang = $this->db->select('barang.*, satuan.id_satuan, satuan.nama_satuan')
            ->from('barang')
            ->join('satuan', 'satuan.id_satuan = barang.satuan_id')
            ->where('id_barang', $barang_id)
            ->get()
            ->row_array();

        if ($barang) {
            $data = [
                'harga_satuan' => $barang['harga_satuan'],
                'stok' => $barang['stok'],
                'satuan_id' => $barang['id_satuan'],
                'nama_satuan' => $barang['nama_satuan']
            ];
        } else {
            $data = [
                'harga_satuan' => 0,
                'stok' => 0,
                'satuan_id' => '',
                'nama_satuan' => ''
            ];
        }

        echo json_encode($data);
    }



}
