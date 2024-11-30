<?= $this->session->flashdata('pesan'); ?>
<div class="card shadow-sm border-bottom-primary">
    <div class="card-header bg-white py-3">
        <div class="row">
            <div class="col">
                <h4 class="h5 align-middle m-0 font-weight-bold text-primary">
                    Data Barang Keluar
                </h4>
            </div>
            <div class="col-auto">
                <a href="<?= base_url('barangkeluar/add') ?>" class="btn btn-sm btn-primary btn-icon-split">
                    <span class="icon">
                        <i class="fa fa-plus"></i>
                    </span>
                    <span class="text">
                        Tambah Barang Keluar
                    </span>
                </a>
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-striped w-100 dt-responsive nowrap" id="dataTable">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>ID Barang Keluar</th>
                    <th>Nama Barang</th>
                    <th>Tanggal Keluar</th>
                    <th>Nama Pembeli</th>
                    <th>Type</th>
                    <th>Jumlah Keluar</th>
                    <th>Harga Satuan</th>
                    <th>Total Harga</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($barangkeluar) : ?>
                    <?php $no = 1; foreach ($barangkeluar as $bk) : ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= $bk['id_barang_keluar']; ?></td>
                            <td><?= $bk['nama_barang']; ?></td>
                            <td><?= date('d-m-Y', strtotime($bk['tanggal_keluar'])); ?></td>
                            <td><?= $bk['nama_distributor']; ?></td>
                            <td><?= $bk['type']; ?></td>
                            <td><?= $bk['jumlah_keluar']; ?></td>
                            <td><?= number_format($bk['harga_satuan'], 0, ',', '.'); ?></td>
                            <td><?= number_format($bk['total_harga'], 0, ',', '.'); ?></td>
                            <td>
                                <a href="<?= base_url('barangkeluar/delete/' . $bk['id_barang_keluar']) ?>" class="btn btn-danger btn-circle btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?');"><i class="fa fa-trash"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="10" class="text-center">Data Kosong</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
