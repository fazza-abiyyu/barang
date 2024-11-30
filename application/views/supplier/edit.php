<div class="card shadow-sm border-bottom-primary">
    <div class="card-header bg-white py-3">
        <div class="row">
            <div class="col">
                <h4 class="h5 align-middle m-0 font-weight-bold text-primary">
                    Form Edit Distributor
                </h4>
            </div>
            <div class="col-auto">
                <a href="<?= base_url('supplier') ?>" class="btn btn-sm btn-primary btn-icon-split">
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
        <form action="<?= base_url('supplier/edit/' . $distributor['id_distributor']) ?>" method="post">
            <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>" />
            <input type="hidden" name="id_distributor" value="<?= $distributor['id_distributor']; ?>">
            <div class="row form-group">
                <div class="col-md-6">
                    <label for="nama_distributor">Nama Distributor</label>
                    <input type="text" name="nama_distributor" id="nama_distributor" class="form-control" value="<?= set_value('nama_distributor', $distributor['nama_distributor']); ?>">
                    <?= form_error('nama_distributor', '<small class="text-danger">', '</small>'); ?>
                </div>
                <div class="col-md-6">
                    <label for="no_telp">Nomor Telepon</label>
                    <input type="text" name="no_telp" id="no_telp" class="form-control" value="<?= set_value('no_telp', $distributor['no_telp']); ?>">
                    <?= form_error('no_telp', '<small class="text-danger">', '</small>'); ?>
                </div>
            </div>
            <div class="row form-group">
                <div class="col-md-12">
                    <label for="alamat">Alamat</label>
                    <textarea name="alamat" id="alamat" class="form-control"><?= set_value('alamat', $distributor['alamat']); ?></textarea>
                    <?= form_error('alamat', '<small class="text-danger">', '</small>'); ?>
                </div>
            </div>
            <div class="row form-group">
                <div class="col-md-12">
                    <label for="type">Tipe</label>
                    <select name="type" id="type" class="form-control">
                        <option value="customer" <?= set_select('type', 'customer', $distributor['type'] == 'customer'); ?>>Customer</option>
                        <option value="distributor" <?= set_select('type', 'distributor', $distributor['type'] == 'distributor'); ?>>Distributor</option>
                    </select>
                    <?= form_error('type', '<small class="text-danger">', '</small>'); ?>
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
