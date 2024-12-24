<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-bottom-primary">
            <div class="card-header bg-white py-3">
                <div class="row">
                    <div class="col">
                        <h4 class="h5 align-middle m-0 font-weight-bold text-primary">
                            Form Input Barang Keluar
                        </h4>
                    </div>
                    <div class="col-auto">
                        <a href="<?= base_url('barangkeluar') ?>" class="btn btn-sm btn-secondary btn-icon-split">
                            <span class="icon">
                                <i class="fa fa-arrow-left"></i>
                            </span>
                            <span class="text">
                                Kembali
                            </span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <?= $this->session->flashdata('pesan'); ?>
                <?= form_open('', [], [
                    'id_barang_keluar' => $id_barang_keluar,
                    'user_id' => $this->session->userdata('login_session')['user'],
                    'satuan_id' => '1' // Set default satuan_id
                ]); ?>

                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="id_barang_keluar">ID Transaksi Barang Keluar</label>
                    <div class="col-md-4">
                        <input value="<?= $id_barang_keluar; ?>" type="text" readonly="readonly" class="form-control">
                        <?= form_error('id_barang_keluar', '<small class="text-danger">', '</small>'); ?>
                    </div>
                </div>

                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="tanggal_keluar">Tanggal Keluar</label>
                    <div class="col-md-4">
                        <input value="<?= set_value('tanggal_keluar', date('Y-m-d')); ?>" name="tanggal_keluar"
                            id="tanggal_keluar" type="text" class="form-control date" placeholder="Tanggal Keluar...">
                        <?= form_error('tanggal_keluar', '<small class="text-danger">', '</small>'); ?>
                    </div>
                </div>

                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="barang_id">Barang</label>
                    <div class="col-md-5">
                        <div class="input-group">
                            <select name="barang_id" id="barang_id" class="custom-select">
                                <option value="" selected disabled>Pilih Barang</option>
                                <?php foreach ($barang as $b): ?>
                                    <option <?= set_select('barang_id', $b['id_barang']) ?> value="<?= $b['id_barang'] ?>">
                                        <?= $b['id_barang'] . ' | ' . $b['nama_barang'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="input-group-append">
                                <a class="btn btn-primary" href="<?= base_url('barang/add'); ?>"><i
                                        class="fa fa-plus"></i></a>
                            </div>
                        </div>
                        <?= form_error('barang_id', '<small class="text-danger">', '</small>'); ?>
                    </div>
                </div>

                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="stok">Stok</label>
                    <div class="col-md-5">
                        <input readonly="readonly" id="stok" type="number" class="form-control">
                    </div>
                </div>

                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="jumlah_keluar">Jumlah Keluar</label>
                    <div class="col-md-5">
                        <div class="input-group">
                            <input value="<?= set_value('jumlah_keluar'); ?>" name="jumlah_keluar" id="jumlah_keluar"
                                type="number" class="form-control" placeholder="Jumlah Keluar...">
                            <div class="input-group-append">
                                <span class="input-group-text" id="satuan">Satuan</span>
                                <input type="hidden" id="satuan_id" name="satuan_id">
                            </div>
                        </div>
                        <?= form_error('jumlah_keluar', '<small class="text-danger">', '</small>'); ?>
                    </div>
                </div>

                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="harga_satuan">Harga Satuan</label>
                    <div class="col-md-5">
                        <input readonly="readonly" id="harga_satuan" type="number" class="form-control">
                    </div>
                </div>

                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="total_harga">Total Harga</label>
                    <div class="col-md-5">
                        <input readonly="readonly" id="total_harga" type="number" class="form-control">
                    </div>
                </div>

                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="pembeli_id">Pilih Pembeli</label>
                    <div class="col-md-5">
                        <select name="pembeli_id" id="pembeli_id" class="custom-select">
                            <option value="" selected disabled>Pilih Pembeli</option>
                            <?php foreach ($distributor as $d): ?>
                                <option value="<?= $d['id_distributor'] ?>" <?= set_select('pembeli_id', $d['id_distributor']); ?>>
                                    <?= $d['nama_distributor'] ?> (<?= $d['type'] ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?= form_error('pembeli_id', '<small class="text-danger">', '</small>'); ?>
                    </div>
                </div>

                <div class="row form-group">
                    <div class="col offset-md-4">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <button type="reset" class="btn btn-secondary">Reset</button>
                    </div>
                </div>

                <?= form_close(); ?>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('barang_id').addEventListener('change', function () {
        var barangId = this.value;
        fetch(`<?= base_url('barangkeluar/getHargaSatuan/') ?>${barangId}`)
            .then(response => response.json())
            .then(data => {
                console.log("Fetched satuan_id: ", data.satuan_id);
                document.getElementById('harga_satuan').value = data.harga_satuan;
                document.getElementById('stok').value = data.stok;
                document.getElementById('satuan_id').value = data.satuan_id;

                document.getElementById('satuan').textContent = data.nama_satuan;

                var jumlahKeluar = document.getElementById('jumlah_keluar').value;
                if (jumlahKeluar) {
                    document.getElementById('total_harga').value = jumlahKeluar * data.harga_satuan;
                }
            });
    });


    document.getElementById('jumlah_keluar').addEventListener('input', function () {
        var jumlahKeluar = parseInt(this.value) || 0;
        var hargaSatuan = parseFloat(document.getElementById('harga_satuan').value) || 0;
        var stok = parseInt(document.getElementById('stok').value) || 0;

        if (jumlahKeluar > stok || jumlahKeluar < 0) {
            this.classList.add('is-invalid');
            document.getElementById('jumlah_keluar_error').style.display = 'block';
            document.getElementById('total_harga').value = '';
        } else {
            this.classList.remove('is-invalid');
            document.getElementById('jumlah_keluar_error').style.display = 'none';
            var totalHarga = jumlahKeluar * hargaSatuan;
            document.getElementById('total_harga').value = totalHarga.toFixed(2);
        }
    });

    document.getElementById('jumlah_keluar').addEventListener('change', function () {
        var jumlahKeluar = parseInt(this.value) || 0;
        var hargaSatuan = parseFloat(document.getElementById('harga_satuan').value) || 0;
        var totalHarga = jumlahKeluar * hargaSatuan;
        document.getElementById('total_harga').value = totalHarga.toFixed(2); 
    });
</script>