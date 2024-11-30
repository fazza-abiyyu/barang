<div class="card shadow-sm border-bottom-primary">
    <div class="card-header bg-white py-3">
        <div class="row">
            <div class="col">
                <h4 class="h5 align-middle m-0 font-weight-bold text-primary">
                    Form Edit Barang
                </h4>
            </div>
            <div class="col-auto">
                <a href="<?= base_url('barang') ?>" class="btn btn-sm btn-primary btn-icon-split">
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
        <form action="" method="post">
            <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>" />
            <input type="hidden" name="id_barang" value="<?= $barang['id_barang']; ?>">
            <div class="row form-group">
                <div class="col-md-6">
                    <label for="nama_barang">Nama Barang</label>
                    <input type="text" name="nama_barang" id="nama_barang" class="form-control" value="<?= set_value('nama_barang', $barang['nama_barang']); ?>">
                    <?= form_error('nama_barang', '<small class="text-danger">', '</small>'); ?>
                </div>
                <div class="col-md-6">
                    <label for="stok">Stok</label>
                    <input type="text" name="stok" id="stok" class="form-control" value="<?= set_value('stok', $barang['stok']); ?>">
                    <?= form_error('stok', '<small class="text-danger">', '</small>'); ?>
                </div>
            </div>
            <div class="row form-group">
                <div class="col-md-6">
                    <label for="harga_satuan">Harga Satuan</label>
                    <input type="text" name="harga_satuan" id="harga_satuan" class="form-control" value="<?= set_value('harga_satuan', $barang['harga_satuan']); ?>">
                    <?= form_error('harga_satuan', '<small class="text-danger">', '</small>'); ?>
                </div>
                <div class="col-md-6">
                    <label for="jenis_id">Jenis Barang</label>
                    <select name="jenis_id" id="jenis_id" class="form-control">
                        <?php foreach ($jenis as $j) : ?>
                            <option value="<?= $j['id_jenis']; ?>" <?= set_select('jenis_id', $j['id_jenis'], $j['id_jenis'] == $barang['jenis_id']); ?>><?= $j['nama_jenis']; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <?= form_error('jenis_id', '<small class="text-danger">', '</small>'); ?>
                </div>
            </div>
            <div class="row form-group">
                <div class="col-md-6">
                    <label for="satuan_id">Satuan Barang</label>
                    <select name="satuan_id" id="satuan_id" class="form-control">
                        <?php foreach ($satuan as $s) : ?>
                            <option value="<?= $s['id_satuan']; ?>" <?= set_select('satuan_id', $s['id_satuan'], $s['id_satuan'] == $barang['satuan_id']); ?>><?= $s['nama_satuan']; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <?= form_error('satuan_id', '<small class="text-danger">', '</small>'); ?>
                </div>
            </div>
            <div class="row form-group">
                <div class="col-md-12 text-right">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>
