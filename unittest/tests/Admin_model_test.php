<?php
use PHPUnit\Framework\TestCase;
use Mockery as m;

class Admin_model_test extends TestCase
{
    private $CI;
    private $admin_model;

    public function setUp(): void
    {
        $this->CI =& get_instance();
        $this->CI->load->model('Admin_model');
        $this->admin_model = $this->CI->Admin_model;
        $this->CI->db = m::mock('CI_DB');
    }

    public function testGet()
    {
        echo "Running testGet\n";
        $table = 'user';
        $where = ['id_user' => 1];
        $expectedResult = (object) ['id_user' => 1, 'nama' => 'Admin'];

        $this->CI->db->shouldReceive('get_where')
            ->with($table, $where)
            ->andReturn($expectedResult);

        $result = $this->admin_model->get($table, $where);
        $this->assertEquals($expectedResult, $result);
    }

    public function testAddBarangKeluar()
    {
        $table = 'barang_keluar';
        $pk = 'id_barang';
        $id = 1;
        $data = ['jumlah_keluar' => 10];

        $this->CI->db->shouldReceive('where')
            ->with($pk, $id)
            ->andReturnSelf();
        $this->CI->db->shouldReceive('update')
            ->with($table, $data)
            ->andReturn(true);

        $result = $this->admin_model->addBarangKeluar($table, $pk, $id, $data);
        $this->assertTrue($result);
    }

    public function testGetBarangMasukWithDetails()
    {
        $expectedResult = [
            ['id_barang_masuk' => 1, 'nama_barang' => 'Barang A', 'harga_satuan' => 10000, 'jumlah_masuk' => 10, 'total_harga' => 100000, 'tanggal_masuk' => '2022-12-12']
        ];

        $this->CI->db->shouldReceive('select')
            ->with('bm.id_barang_masuk, b.nama_barang, bm.harga_satuan, bm.jumlah_masuk, (bm.harga_satuan * bm.jumlah_masuk) as total_harga, bm.tanggal_masuk')
            ->andReturnSelf();
        $this->CI->db->shouldReceive('from')
            ->with('barang_masuk bm')
            ->andReturnSelf();
        $this->CI->db->shouldReceive('join')
            ->with('barang b', 'bm.barang_id = b.id_barang')
            ->andReturnSelf();
        $this->CI->db->shouldReceive('order_by')
            ->with('bm.tanggal_masuk', 'DESC')
            ->andReturnSelf();
        $this->CI->db->shouldReceive('get')
            ->andReturn((object) ['result_array' => $expectedResult]);

        $result = $this->admin_model->getBarangMasukWithDetails();
        $this->assertEquals($expectedResult, $result);
    }

    public function testGetBarangKeluar()
    {
        $expectedResult = [
            ['id_barang_keluar' => 1, 'nama_barang' => 'Barang B', 'stok' => 5, 'nama_distributor' => 'Distributor A', 'tanggal_keluar' => '2022-12-12']
        ];

        $this->CI->db->shouldReceive('select')
            ->with('barang_keluar.*, barang.nama_barang, barang.stok, distributor.nama_distributor, distributor.type')
            ->andReturnSelf();
        $this->CI->db->shouldReceive('from')
            ->with('barang_keluar')
            ->andReturnSelf();
        $this->CI->db->shouldReceive('join')
            ->with('barang', 'barang_keluar.barang_id = barang.id_barang', 'left')
            ->andReturnSelf();
        $this->CI->db->shouldReceive('join')
            ->with('distributor', 'barang_keluar.pembeli_id = distributor.id_distributor', 'left')
            ->andReturnSelf();
        $this->CI->db->shouldReceive('order_by')
            ->with('barang_keluar.tanggal_keluar', 'DESC')
            ->andReturnSelf();
        $this->CI->db->shouldReceive('get')
            ->andReturn((object) ['result_array' => $expectedResult]);

        $result = $this->admin_model->getBarangKeluar();
        $this->assertEquals($expectedResult, $result);
    }

    public function testUpdate()
    {
        $table = 'barang';
        $pk = 'id_barang';
        $id = 1;
        $data = ['nama_barang' => 'Updated Name'];

        $this->CI->db->shouldReceive('where')
            ->with($pk, $id)
            ->andReturnSelf();
        $this->CI->db->shouldReceive('update')
            ->with($table, $data)
            ->andReturn(true);

        $result = $this->admin_model->update($table, $pk, $id, $data);
        $this->assertTrue($result);
    }

    public function testInsert()
    {
        $table = 'barang';
        $data = ['nama_barang' => 'New Item'];

        $this->CI->db->shouldReceive('insert')
            ->with($table, $data)
            ->andReturn(true);

        $result = $this->admin_model->insert($table, $data);
        $this->assertTrue($result);
    }

    public function testDelete()
    {
        $table = 'barang';
        $pk = 'id_barang';
        $id = 1;

        $this->CI->db->shouldReceive('delete')
            ->with($table, [$pk => $id])
            ->andReturn(true);

        $result = $this->admin_model->delete($table, $pk, $id);
        $this->assertTrue($result);
    }

    public function testGetUsers()
    {
        $id = 1;
        $expectedResult = [
            ['id_user' => 2, 'nama' => 'User1'],
            ['id_user' => 3, 'nama' => 'User2']
        ];

        $this->CI->db->shouldReceive('where')
            ->with('id_user !=', $id)
            ->andReturnSelf();
        $this->CI->db->shouldReceive('get')
            ->with('user')
            ->andReturn((object) ['result_array' => $expectedResult]);

        $result = $this->admin_model->getUsers($id);
        $this->assertEquals($expectedResult, $result);
    }

    public function testGetAllDistributors()
    {
        $expectedResult = [
            ['id_distributor' => 1, 'nama_distributor' => 'Distributor1'],
            ['id_distributor' => 2, 'nama_distributor' => 'Distributor2']
        ];

        $this->CI->db->shouldReceive('get')
            ->with('distributor')
            ->andReturn((object) ['result_array' => $expectedResult]);

        $result = $this->admin_model->getAllDistributors();
        $this->assertEquals($expectedResult, $result);
    }

    public function testGetBarang()
    {
        $expectedResult = [
            ['id_barang' => 1, 'nama_barang' => 'Barang1', 'nama_jenis' => 'Jenis1', 'stok' => 10, 'nama_satuan' => 'Satuan1', 'harga_satuan' => 10000],
            ['id_barang' => 2, 'nama_barang' => 'Barang2', 'nama_jenis' => 'Jenis2', 'stok' => 20, 'nama_satuan' => 'Satuan2', 'harga_satuan' => 20000]
        ];

        $this->CI->db->shouldReceive('select')
            ->with('barang.id_barang, barang.nama_barang, jenis.nama_jenis, barang.stok, satuan.nama_satuan, barang.harga_satuan')
            ->andReturnSelf();
        $this->CI->db->shouldReceive('from')
            ->with('barang')
            ->andReturnSelf();
        $this->CI->db->shouldReceive('join')
            ->with('jenis', 'barang.jenis_id = jenis.id_jenis')
            ->andReturnSelf();
        $this->CI->db->shouldReceive('join')
            ->with('satuan', 'barang.satuan_id = satuan.id_satuan')
            ->andReturnSelf();
        $this->CI->db->shouldReceive('get')
            ->andReturn((object) ['result_array' => $expectedResult]);

        $result = $this->admin_model->getBarang();
        $this->assertEquals($expectedResult, $result);
    }

    protected function tearDown(): void
    {
        m::close();
    }

    // Tambahkan pengujian unit untuk metode lain sesuai kebutuhan
}
