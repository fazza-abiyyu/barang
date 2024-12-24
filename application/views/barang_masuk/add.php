<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-bottom-primary">
            <div class="card-header bg-white py-3">
                <div class="row">
                    <div class="col">
                        <h4 class="h5 align-middle m-0 font-weight-bold text-primary">
                            Form Input Barang Masuk
                        </h4>
                    </div>
                    <div class="col-auto">
                        <a href="<?= base_url('barangmasuk') ?>" class="btn btn-sm btn-secondary btn-icon-split">
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
                <?= form_open('', [], ['id_barang_masuk' => $id_barang_masuk, 'user_id' => $this->session->userdata('login_session')['user']]); ?>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="id_barang_masuk">ID Transaksi Barang Masuk</label>
                    <div class="col-md-4">
                        <input value="<?= $id_barang_masuk; ?>" type="text" readonly="readonly" class="form-control">
                        <?= form_error('id_barang_masuk', '<small class="text-danger">', '</small>'); ?>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="tanggal_masuk">Tanggal Masuk</label>
                    <div class="col-md-4">
                        <input value="<?= set_value('tanggal_masuk', date('Y-m-d')); ?>" name="tanggal_masuk"
                            id="tanggal_masuk" type="text" class="form-control date" placeholder="Tanggal Masuk...">
                        <?= form_error('tanggal_masuk', '<small class="text-danger">', '</small>'); ?>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="barang_id">Barang</label>
                    <div class="col-md-5">
                        <div class="input-group">
                            <select name="barang_id" id="barang_id" class="custom-select">
                                <option value="" selected disabled>Pilih Barang</option>
                                <?php foreach ($barang as $b): ?>
                                    <option <?= $this->uri->segment(3) == $b['id_barang'] ? 'selected' : ''; ?>
                                        <?= set_select('barang_id', $b['id_barang']) ?> value="<?= $b['id_barang'] ?>">
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
                    <label class="col-md-4 text-md-right" for="jumlah_masuk">Jumlah Masuk</label>
                    <div class="col-md-5">
                        <div class="input-group">
                            <input value="<?= set_value('jumlah_masuk'); ?>" name="jumlah_masuk" id="jumlah_masuk"
                                type="number" class="form-control" placeholder="Jumlah Masuk...">
                            <div class="input-group-append">
                                <span class="input-group-text" id="satuan">Satuan</span>
                                <input type="hidden" id="satuan_id" name="satuan_id">
                            </div>
                        </div>
                        <?= form_error('jumlah_masuk', '<small class="text-danger">', '</small>'); ?>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="harga_satuan">Harga Satuan</label>
                    <div class="col-md-5">
                        <input readonly="readonly" id="harga_satuan" type="number" class="form-control">
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="total_stok">Total Stok</label>
                    <div class="col-md-5">
                        <input readonly="readonly" id="total_stok" type="number" class="form-control">
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="total_harga_barang">Total Harga</label>
                    <div class="col-md-5">
                        <input readonly="readonly" id="total_harga_barang" type="number" class="form-control">
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
    document.addEventListener('DOMContentLoaded', function () {
        const barangSelect = document.getElementById('barang_id');
        const hargaSatuanInput = document.getElementById('harga_satuan');
        const jumlahMasukInput = document.getElementById('jumlah_masuk');
        const totalHargaInput = document.getElementById('total_harga_barang');
        const satuanSpan = document.getElementById('satuan');
        const satuanIdInput = document.getElementById('satuan_id');
        const stokInput = document.getElementById('stok');

        barangSelect.addEventListener('change', function () {
            const barangId = this.value;
            if (!barangId) return;

            fetch('<?= base_url("barangmasuk/get_harga_satuan/") ?>' + barangId)
                .then(response => response.json())
                .then(data => {
                    console.log('Data yang diterima:', data);

                    if (data.harga_satuan && data.satuan_id) {
                        hargaSatuanInput.value = data.harga_satuan;
                        satuanSpan.textContent = data.nama_satuan || 'Satuan';
                        satuanIdInput.value = data.satuan_id;
                        stokInput.value = data.stok || 0;
                        hitungTotalHarga();
                    } else {
                        hargaSatuanInput.value = 0;
                        satuanSpan.textContent = 'Satuan';
                        satuanIdInput.value = '';
                        stokInput.value = 0;
                        console.error('Data tidak lengkap:', data);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    hargaSatuanInput.value = 0;
                    satuanSpan.textContent = 'Satuan';
                    satuanIdInput.value = '';
                    stokInput.value = 0;
                });
        });

        document.querySelector('form').addEventListener('submit', function (event) {
            if (!satuanIdInput.value || satuanIdInput.value === 'undefined') {
                event.preventDefault();
                alert('Silakan pilih barang terlebih dahulu!');
                return false;
            }
        });

        jumlahMasukInput.addEventListener('input', function () {
            hitungTotalHarga();
        });

        function hitungTotalHarga() {
            const hargaSatuan = parseFloat(hargaSatuanInput.value) || 0;
            const jumlahMasuk = parseFloat(jumlahMasukInput.value) || 0;
            const total = hargaSatuan * jumlahMasuk;
            totalHargaInput.value = total.toFixed(2);
        }
    });

</script>