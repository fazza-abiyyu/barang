<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Laporan extends CI_Controller
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
        $this->form_validation->set_rules('transaksi', 'Transaksi', 'required|in_list[barang_masuk,barang_keluar]');
        $this->form_validation->set_rules('tanggal', 'Periode Tanggal', 'required');

        if ($this->form_validation->run() == false) {
            $data['title'] = "Laporan Transaksi";
            $this->template->load('templates/dashboard', 'laporan/form', $data);
        } else {
            $input = $this->input->post(null, true);
            $table = $input['transaksi'];
            $tanggal = $input['tanggal'];
            $pecah = explode(' - ', $tanggal);
            $mulai = date('Y-m-d', strtotime($pecah[0]));
            $akhir = date('Y-m-d', strtotime(end($pecah)));

            if ($table == 'barang_masuk') {
                $query = $this->admin->getBarangMasukWithDetails($mulai, $akhir);
            } else {
                $query = $this->admin->getBarangKeluar($mulai, $akhir);
            }

            $this->_cetak($query, $table, $tanggal);
        }
    }

    private function _cetak($data, $table_, $tanggal)
    {
        ob_start(); // Mulai output buffering

        $this->load->library('CustomPDF');
        $table = $table_ == 'barang_masuk' ? 'Barang Masuk' : 'Barang Keluar';

        $pdf = new FPDF();
        $pdf->AddPage('P', 'Letter');
        $pdf->SetFont('Times', 'B', 16);
        $pdf->Cell(190, 7, 'Laporan ' . $table, 0, 1, 'C');
        $pdf->SetFont('Times', '', 10);
        $pdf->Cell(190, 4, 'Tanggal : ' . $tanggal, 0, 1, 'C');
        $pdf->Ln(10);

        $pdf->SetFont('Arial', 'B', 10);

        if ($table_ == 'barang_masuk') :
            $pdf->Cell(10, 7, 'No.', 1, 0, 'C');
            $pdf->Cell(35, 7, 'Tanggal', 1, 0, 'C');
            $pdf->Cell(55, 7, 'Nama Barang', 1, 0, 'C');
            $pdf->Cell(30, 7, 'Stok', 1, 0, 'C');
            $pdf->Cell(30, 7, 'Harga Satuan', 1, 0, 'C');
            $pdf->Cell(30, 7, 'Jumlah', 1, 0, 'C');
            $pdf->Ln();

            $no = 1;
            foreach ($data as $d) {
                $pdf->SetFont('Arial', '', 10);
                $pdf->Cell(10, 7, $no++ . '.', 1, 0, 'C');
                $pdf->Cell(35, 7, date('d-m-Y', strtotime($d['tanggal_masuk'])), 1, 0, 'C');
                $pdf->Cell(55, 7, $d['nama_barang'], 1, 0, 'L');
                $pdf->Cell(30, 7, $d['stok'], 1, 0, 'C');
                $pdf->Cell(30, 7, number_format($d['harga_satuan'], 0, ',', '.'), 1, 0, 'C');
                $pdf->Cell(30, 7, $d['jumlah_masuk'], 1, 0, 'C');
                $pdf->Ln();
            }
        else :
            $pdf->SetFont('Arial', '', 7);
            $pdf->Cell(10, 7, 'No.', 1, 0, 'C');
            $pdf->Cell(25, 7, 'ID Barang', 1, 0, 'C');
            $pdf->Cell(25, 7, 'Tanggal', 1, 0, 'C');
            $pdf->Cell(30, 7, 'Nama Barang', 1, 0, 'C');
            $pdf->Cell(30, 7, 'Nama Pembeli', 1, 0, 'C');
            $pdf->Cell(15, 7, 'Role', 1, 0, 'C');
            $pdf->Cell(15, 7, 'Jumlah', 1, 0, 'C');
            $pdf->Cell(20, 7, 'Harga Satuan', 1, 0, 'C');
            $pdf->Cell(20, 7, 'Total Harga', 1, 0, 'C');
            $pdf->Ln();

            $no = 1;
            foreach ($data as $d) {
                $pdf->SetFont('Arial', '', 7);
                $pdf->Cell(10, 7, $no++ . '.', 1, 0, 'C');
                $pdf->Cell(25, 7, $d['id_barang_keluar'], 1, 0, 'C');
                $pdf->Cell(25, 7, date('d-m-Y', strtotime($d['tanggal_keluar'])), 1, 0, 'C');
                $pdf->Cell(30, 7, $d['nama_barang'], 1, 0, 'L');
                $pdf->Cell(30, 7, $d['nama_distributor'], 1, 0, 'L');
                $pdf->Cell(15, 7, $d['type'], 1, 0, 'C');
                $pdf->Cell(15, 7, $d['jumlah_keluar'], 1, 0, 'C');
                $pdf->Cell(20, 7, number_format($d['harga_satuan'], 0, ',', '.'), 1, 0, 'C');
                $pdf->Cell(20, 7, number_format($d['total_harga'], 0, ',', '.'), 1, 0, 'C');

                $pdf->Ln();
            }
        endif;

        $file_name = $table . ' ' . $tanggal;
        ob_end_clean();
        $pdf->Output('I', $file_name);
    }
}
