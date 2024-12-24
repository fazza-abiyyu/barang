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
        ob_start();
    
        $this->load->library('CustomPDF');
        $table = $table_ == 'barang_masuk' ? 'Barang Masuk' : 'Barang Keluar';
    
        $pdf = new FPDF();
        $pdf->AddPage('L', 'Letter');
        $pdf->SetFont('Times', 'B', 16);
        $pdf->Cell(250, 7, 'Laporan ' . $table, 0, 1, 'C');
        $pdf->SetFont('Times', '', 10);
        $pdf->Cell(250, 4, 'Tanggal : ' . $tanggal, 0, 1, 'C');
        $pdf->Ln(10);
    
        $pdf->SetFont('Arial', 'B', 11);
    
        if ($table_ == 'barang_masuk') {
            $widths = [
                'no' => 10,
                'id_barang_masuk' => 35,
                'tanggal_masuk' => 35,
                'nama_barang' => 35,
                'harga_satuan' => 30,
                'jumlah_masuk' => 30,
                'total_harga' => 30
            ];
    
            $pdf->Cell($widths['no'], 7, 'No.', 1, 0, 'C');
            $pdf->Cell($widths['id_barang_masuk'], 7, 'ID Barang Masuk', 1, 0, 'C');
            $pdf->Cell($widths['tanggal_masuk'], 7, 'Tanggal Masuk', 1, 0, 'C');
            $pdf->Cell($widths['nama_barang'], 7, 'Nama Barang', 1, 0, 'C');
            $pdf->Cell($widths['harga_satuan'], 7, 'Harga Satuan', 1, 0, 'C');
            $pdf->Cell($widths['jumlah_masuk'], 7, 'Jumlah Masuk', 1, 0, 'C');
            $pdf->Cell($widths['total_harga'], 7, 'Total Harga', 1, 0, 'C');
            $pdf->Ln();
    

            $no = 1;
            $totalTransaksi = 0;
            foreach ($data as $d) {
                $pdf->SetFont('Arial', '', 11);
                $pdf->Cell($widths['no'], 7, $no++ . '.', 1, 0, 'C');
                $pdf->Cell($widths['id_barang_masuk'], 7, $d['id_barang_masuk'], 1, 0, 'C');
                $pdf->Cell($widths['tanggal_masuk'], 7, date('d-m-Y', strtotime($d['tanggal_masuk'])), 1, 0, 'C');
                $pdf->Cell($widths['nama_barang'], 7, $d['nama_barang'], 1, 0, 'L');
                $pdf->Cell($widths['harga_satuan'], 7, number_format($d['harga_satuan'], 0, ',', '.'), 1, 0, 'C');
                $pdf->Cell($widths['jumlah_masuk'], 7, $d['jumlah_masuk'], 1, 0, 'C');
                $pdf->Cell($widths['total_harga'], 7, number_format($d['total_harga'], 0, ',', '.'), 1, 0, 'C');
                $pdf->Ln();
    
                $totalTransaksi += $d['total_harga'];
            }
    
            $pdf->SetFont('Arial', 'B', 11);
            $pdf->Cell(array_sum($widths) - $widths['total_harga'], 7, 'Total Transaksi', 1, 0, 'C');
            $pdf->Cell($widths['total_harga'], 7, number_format($totalTransaksi, 0, ',', '.'), 1, 0, 'C');
            $pdf->Ln();
    
        } else {
            
            $widths = [
                'no' => 10,
                'id_barang_keluar' => 35,
                'nama_barang' => 35,
                'tanggal_keluar' => 30,
                'nama_distributor' => 30,
                'type' => 25,
                'jumlah_keluar' => 30,
                'harga_satuan' => 30,
                'total_harga' => 30
            ];

            $pdf->Cell($widths['no'], 7, 'No.', 1, 0, 'C');
            $pdf->Cell($widths['id_barang_keluar'], 7, 'ID Barang Keluar', 1, 0, 'C');
            $pdf->Cell($widths['nama_barang'], 7, 'Nama Barang', 1, 0, 'C');
            $pdf->Cell($widths['tanggal_keluar'], 7, 'Tanggal Keluar', 1, 0, 'C');
            $pdf->Cell($widths['nama_distributor'], 7, 'Nama Distributor', 1, 0, 'C');
            $pdf->Cell($widths['type'], 7, 'Type', 1, 0, 'C');
            $pdf->Cell($widths['jumlah_keluar'], 7, 'Jumlah Keluar', 1, 0, 'C');
            $pdf->Cell($widths['harga_satuan'], 7, 'Harga Satuan', 1, 0, 'C');
            $pdf->Cell($widths['total_harga'], 7, 'Total Harga', 1, 0, 'C');
            $pdf->Ln();
    
            $no = 1;
            $totalTransaksi = 0;
            foreach ($data as $d) {
                $pdf->SetFont('Arial', '', 11);
                $pdf->Cell($widths['no'], 7, $no++ . '.', 1, 0, 'C');
                $pdf->Cell($widths['id_barang_keluar'], 7, $d['id_barang_keluar'], 1, 0, 'C');
                $pdf->Cell($widths['nama_barang'], 7, $d['nama_barang'], 1, 0, 'L');
                $pdf->Cell($widths['tanggal_keluar'], 7, date('d-m-Y', strtotime($d['tanggal_keluar'])), 1, 0, 'C');
                $pdf->Cell($widths['nama_distributor'], 7, $d['nama_distributor'], 1, 0, 'L');
                $pdf->Cell($widths['type'], 7, $d['type'], 1, 0, 'C');
                $pdf->Cell($widths['jumlah_keluar'], 7, $d['jumlah_keluar'], 1, 0, 'C');
                $pdf->Cell($widths['harga_satuan'], 7, number_format($d['harga_satuan'], 0, ',', '.'), 1, 0, 'C');
                $pdf->Cell($widths['total_harga'], 7, number_format($d['total_harga'], 0, ',', '.'), 1, 0, 'C');
                $pdf->Ln();
    
                $totalTransaksi += $d['total_harga'];
            }
    
            $pdf->SetFont('Arial', 'B', 11);
            $pdf->Cell(array_sum($widths) - $widths['total_harga'], 7, 'Total Transaksi', 1, 0, 'C');
            $pdf->Cell($widths['total_harga'], 7, number_format($totalTransaksi, 0, ',', '.'), 1, 0, 'C');
            $pdf->Ln();
        }
    
        $file_name = $table . ' ' . $tanggal;
        ob_end_clean();
        $pdf->Output('I', $file_name);
    }
    

}
