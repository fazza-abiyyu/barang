<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin_model extends CI_Model
{
    public function get($table, $data = null, $where = null)
    {
        if ($data != null) {
            return $this->db->get_where($table, $data)->row_array();
        } else {
            return $this->db->get_where($table, $where)->result_array();
        }
    }
    public function addBarangKeluar($table, $pk, $id, $data)
    {
        $this->db->where($pk, $id);
        return $this->db->update($table, $data);
    }

    public function getBarangMasukWithDetails()
    {
        $this->db->select('bm.id_barang_masuk, b.nama_barang, bm.harga_satuan, bm.jumlah_masuk, (bm.harga_satuan * bm.jumlah_masuk) as total_harga, bm.tanggal_masuk');
        $this->db->from('barang_masuk bm');
        $this->db->join('barang b', 'bm.barang_id = b.id_barang');
        $this->db->order_by('bm.tanggal_masuk', 'DESC');
        $query = $this->db->get();
        return $query->result_array();
    }
    public function getBarangKeluar()
{
    $this->db->select('barang_keluar.*, barang.nama_barang, barang.stok, distributor.nama_distributor, distributor.type');
    $this->db->from('barang_keluar');
    $this->db->join('barang', 'barang_keluar.barang_id = barang.id_barang', 'left');
    $this->db->join('distributor', 'barang_keluar.pembeli_id = distributor.id_distributor', 'left');
    $this->db->order_by('barang_keluar.tanggal_keluar', 'DESC');
    return $this->db->get()->result_array();
}


    public function update($table, $pk, $id, $data)
    {
        $this->db->where($pk, $id);
        return $this->db->update($table, $data);
    }



    public function insert($table, $data, $batch = false)
    {
        return $batch ? $this->db->insert_batch($table, $data) : $this->db->insert($table, $data);
    }

    public function delete($table, $pk, $id)
    {
        return $this->db->delete($table, [$pk => $id]);
    }

    public function getUsers($id)
    {
        /**
         * ID disini adalah untuk data yang tidak ingin ditampilkan. 
         * Maksud saya disini adalah 
         * tidak ingin menampilkan data user yang digunakan, 
         * pada managemen data user
         */
        $this->db->where('id_user !=', $id);
        return $this->db->get('user')->result_array();
    }
    public function getAllDistributors()
    {
        return $this->db->get('distributor')->result_array();
    }

    public function getBarang()
    {
        $this->db->select('barang.id_barang, barang.nama_barang, jenis.nama_jenis, barang.stok, satuan.nama_satuan, barang.harga_satuan');
        $this->db->from('barang');
        $this->db->join('jenis', 'barang.jenis_id = jenis.id_jenis');
        $this->db->join('satuan', 'barang.satuan_id = satuan.id_satuan');
        $query = $this->db->get();
        return $query->result_array();
    }


    public function getBarangMasuk($limit = null, $id_barang = null, $range = null)
    {
        $this->db->select('*');
        $this->db->join('user u', 'bm.user_id = u.id_user');
        $this->db->join('distributor sp', 'bm.id_distributor = sp.id_distributor');
        $this->db->join('barang b', 'bm.barang_id = b.id_barang');
        $this->db->join('satuan s', 'b.satuan_id = s.id_satuan');
        if ($limit != null) {
            $this->db->limit($limit);
        }

        if ($id_barang != null) {
            $this->db->where('id_barang', $id_barang);
        }

        if ($range != null) {
            $this->db->where('tanggal_masuk >=', $range['mulai']);
            $this->db->where('tanggal_masuk <=', $range['akhir']);
        }

        $this->db->order_by('id_barang_masuk', 'DESC');
        return $this->db->get('barang_masuk bm')->result_array();
    }
    public function updateStokBarang($barang_id, $jumlah_masuk)
    {
        // Ambil stok barang saat ini
        $barang = $this->getBarang();
        $barang = array_filter($barang, function ($b) use ($barang_id) {
            return $b['id_barang'] == $barang_id;
        });
        $barang = reset($barang);

        if ($barang) {
            $stok_sekarang = $barang['stok'];
            $stok_baru = $stok_sekarang + $jumlah_masuk;

            // Update stok barang
            $this->db->set('stok', $stok_baru);
            $this->db->where('id_barang', $barang_id);
            return $this->db->update('barang');
        }
        return false;
    }



    public function getMax($table, $column, $prefix)
    {
        $this->db->select_max($column, 'max');
        $this->db->like($column, $prefix, 'after');
        $query = $this->db->get($table);
        return $query->row_array()['max'] ?? null;
    }



    public function count($table)
    {
        return $this->db->count_all($table);
    }

    public function sum($table, $column)
    {
        $this->db->select_sum($column);
        $query = $this->db->get($table);
        return $query->row()->$column;
    }

    public function min($table, $column, $limit)
    {
        $this->db->select_min($column);
        $this->db->limit($limit);
        $query = $this->db->get($table);
        return $query->row()->$column;
    }

    public function chartBarangMasuk($bulan)
    {
        $like = 'T-BM-' . date('y') . $bulan;
        $this->db->like('id_barang_masuk', $like, 'after');
        return count($this->db->get('barang_masuk')->result_array());
    }

    public function chartBarangKeluar($bulan)
    {
        $like = 'T-BK-' . date('y') . $bulan;
        $this->db->like('id_barang_keluar', $like, 'after');
        return count($this->db->get('barang_keluar')->result_array());
    }

    public function laporan($table, $mulai, $akhir)
    {
        $tgl = $table == 'barang_masuk' ? 'tanggal_masuk' : 'tanggal_keluar';
        $this->db->where($tgl . ' >=', $mulai);
        $this->db->where($tgl . ' <=', $akhir);
        return $this->db->get($table)->result_array();
    }

    public function cekStok($id)
    {
        $this->db->join('satuan s', 'b.satuan_id=s.id_satuan');
        return $this->db->get_where('barang b', ['id_barang' => $id])->row_array();
    }
    public function getBarangMin()
    {
        $this->db->select('id_barang, nama_barang, stok');
        $this->db->from('barang');
        $this->db->where('stok <', 10);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getLastBarangMasuk()
    {
        $this->db->select('tanggal_masuk, nama_barang, jumlah_masuk');
        $this->db->from('barang_masuk');
        $this->db->join('barang', 'barang_masuk.barang_id = barang.id_barang');
        $this->db->order_by('tanggal_masuk', 'DESC');
        $this->db->limit(5);
        $query = $this->db->get();
        return $query->result_array();
    }
    public function get_barang_masuk()
    {
        $this->db->select('id_barang_masuk, nama_barang, stok, harga_satuan, jumlah_masuk, (harga_satuan * jumlah_masuk) AS total_harga_barang, tanggal_masuk');
        $query = $this->db->get('barang_masuk');
        return $query->result_array();
    }
    public function getHargaSatuanBarang($barang_id)
    {
        $this->db->select('harga_satuan');
        $this->db->from('barang');
        $this->db->where('id_barang', $barang_id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->row()->harga_satuan;
        }
        return 0;
    }
    public function getAllSatuan()
    {
        return $this->db->get('satuan')->result_array();
    }



}
