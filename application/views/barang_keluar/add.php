<form action="<?= base_url('barangkeluar/add') ?>" method="post">
    <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
    <input type="hidden" name="id_barang_keluar" value="<?= isset($id_barang_keluar) ? $id_barang_keluar : ''; ?>">
    <div class="form-group">
        <label for="barang_id">Barang</label>
        <select name="barang_id" id="barang_id" class="form-control" required>
            <option value="">Pilih Barang</option>
            <?php foreach ($barang as $b) : ?>
                <option value="<?= $b['id_barang'] ?>"><?= $b['nama_barang'] ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-group">
        <label for="stok">Stok</label>
        <input type="text" name="stok" id="stok" class="form-control" readonly>
    </div>
    <div class="form-group">
        <label for="jumlah_keluar">Jumlah Keluar</label>
        <input type="number" name="jumlah_keluar" id="jumlah_keluar" class="form-control" required>
        <small id="jumlah_keluar_error" class="form-text text-danger" style="display: none;">Jumlah keluar tidak boleh melebihi stok yang tersedia atau bernilai negatif</small>
    </div>
    <div class="form-group">
        <label for="harga_satuan">Harga Satuan</label>
        <input type="text" name="harga_satuan" id="harga_satuan" class="form-control" readonly>
    </div>
    <div class="form-group">
        <label for="total_harga">Total Harga</label>
        <input type="text" name="total_harga" id="total_harga" class="form-control" readonly>
    </div>
    <div class="form-group">
        <label for="tanggal_keluar">Tanggal Keluar</label>
        <input type="date" name="tanggal_keluar" id="tanggal_keluar" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="pembeli_id">Pembeli</label>
        <select name="pembeli_id" id="pembeli_id" class="form-control" required>
            <option value="">Pilih Pembeli</option>
            <?php foreach ($distributor as $d) : ?>
                <option value="<?= $d['id_distributor'] ?>"><?= $d['nama_distributor'] ?> (<?= $d['type'] ?>)</option>
            <?php endforeach; ?>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Simpan</button>
</form>

<script>
    document.getElementById('barang_id').addEventListener('change', function() {
        var barangId = this.value;
        // Request untuk mendapatkan harga satuan dan stok barang
        fetch(`<?= base_url('barangkeluar/getHargaSatuan/') ?>${barangId}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('harga_satuan').value = data.harga_satuan;
                document.getElementById('stok').value = data.stok;
                var jumlahKeluar = document.getElementById('jumlah_keluar').value;
                if (jumlahKeluar) {
                    document.getElementById('total_harga').value = jumlahKeluar * data.harga_satuan;
                }
            });
    });

    document.getElementById('jumlah_keluar').addEventListener('input', function() {
        var jumlahKeluar = parseInt(this.value);
        var hargaSatuan = parseFloat(document.getElementById('harga_satuan').value);
        var stok = parseInt(document.getElementById('stok').value);

        // Kondisi untuk memeriksa apakah jumlah keluar melebihi stok atau bernilai negatif
        if (jumlahKeluar > stok || jumlahKeluar < 0) {
            this.classList.add('is-invalid');
            document.getElementById('jumlah_keluar_error').style.display = 'block';
            document.getElementById('total_harga').value = '';
        } else {
            this.classList.remove('is-invalid');
            document.getElementById('jumlah_keluar_error').style.display = 'none';
            document.getElementById('total_harga').value = jumlahKeluar * hargaSatuan;
        }
    });
</script>
