<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Barangmasuk extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        cek_login();

        $this->load->model('Admin_model', 'admin');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $data['title'] = "Barang Masuk";
        $data['barangmasuk'] = $this->admin->getBarangMasukWithDetails();
        $this->template->load('templates/dashboard', 'barang_masuk/data', $data);
    }

    private function _validasi()
    {
        $this->form_validation->set_rules('tanggal_masuk', 'Tanggal Masuk', 'required|trim');
        $this->form_validation->set_rules('barang_id', 'Barang', 'required');
        $this->form_validation->set_rules('jumlah_masuk', 'Jumlah Masuk', 'required|trim|numeric|greater_than[0]');
    }

    public function add()
    {
        $this->_validasi();

        if ($this->form_validation->run() == false) {
            $data['title'] = "Barang Masuk";
            $data['barang'] = $this->admin->getBarang();

            $kode = 'T-BM-' . date('ymd');
            $kode_terakhir = $this->admin->getMax('barang_masuk', 'id_barang_masuk', $kode);
            $kode_tambah = substr($kode_terakhir, -5, 5);
            $kode_tambah++;
            $number = str_pad($kode_tambah, 5, '0', STR_PAD_LEFT);
            $data['id_barang_masuk'] = $kode . $number;

            $this->template->load('templates/dashboard', 'barang_masuk/add', $data);
        } else {
            $input = $this->input->post(null, true);

            $harga_satuan = $this->admin->getHargaSatuanBarang($input['barang_id']);
            if (!$harga_satuan) {
                set_pesan('Harga satuan barang tidak ditemukan!');
                redirect('barangmasuk/add');
            }

            $total_harga = $harga_satuan * $input['jumlah_masuk'];

            $input['harga_satuan'] = $harga_satuan;
            $input['total_harga'] = $total_harga;
            $input['satuan_id'] = $this->input->post('satuan_id');

            log_message('error', 'satuan_id: ' . $input['satuan_id']);

            $insert = $this->admin->insert('barang_masuk', $input);

            if ($insert) {
                $this->updateStokBarang($input['barang_id'], $input['jumlah_masuk']);

                set_pesan('Data berhasil disimpan.');
                redirect('barangmasuk');
            } else {
                set_pesan('Opps ada kesalahan!');
                redirect('barangmasuk/add');
            }
        }
    }

    private function updateStokBarang($barang_id, $jumlah_masuk)
    {
        $stok_lama = $this->admin->get('barang', ['id_barang' => $barang_id])['stok'];
        $stok_baru = $stok_lama + $jumlah_masuk;

        $this->admin->update('barang', 'id_barang', $barang_id, ['stok' => $stok_baru]);
    }



    public function delete($getId)
    {
        $id = encode_php_tags($getId);
        if ($this->admin->delete('barang_masuk', 'id_barang_masuk', $id)) {
            set_pesan('Data berhasil dihapus.');
        } else {
            set_pesan('Data gagal dihapus.', false);
        }
        redirect('barangmasuk');
    }

    public function get_harga_satuan($barang_id)
    {
        $barang = $this->db->select('harga_satuan, satuan.id_satuan as satuan_id, satuan.nama_satuan, stok')
            ->from('barang')
            ->join('satuan', 'satuan.id_satuan = barang.satuan_id')
            ->where('id_barang', $barang_id)
            ->get()
            ->row();

        if ($barang) {
            $data = [
                'harga_satuan' => $barang->harga_satuan,
                'satuan_id' => $barang->satuan_id,
                'nama_satuan' => $barang->nama_satuan,
                'stok' => $barang->stok
            ];
        } else {
            $data = [
                'harga_satuan' => 0,
                'satuan_id' => '',
                'nama_satuan' => '',
                'stok' => 0
            ];
        }

        echo json_encode($data);
    }

}
