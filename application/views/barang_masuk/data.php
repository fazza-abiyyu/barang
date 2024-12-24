<!-- <div class="row justify-content-center">
    <div class="col-lg-12">
        <div class="card shadow-sm border-bottom-primary">
            <div class="card-header bg-white py-3">
                <div class="row">
                    <div class="col">
                        <h4 class="h5 align-middle m-0 font-weight-bold text-primary">
                            Data Barang Masuk
                        </h4>
                    </div>
                    <div class="col-auto">
                        <a href="<?= base_url('barangmasuk/add') ?>" class="btn btn-sm btn-primary btn-icon-split">
                            <span class="icon">
                                <i class="fa fa-plus"></i>
                            </span>
                            <span class="text">
                                Tambah Data
                            </span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th scope="col">NO</th>
                            <th scope="col">ID Barang Masuk</th>
                            <th scope="col">Nama Barang</th>
                            <th scope="col">Tanggal Masuk</th>
                            <th scope="col">Jumlah Masuk</th>
                            <th scope="col">Harga Satuan</th>
                            <th scope="col">Total Harga</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($barangmasuk): ?>
                            <?php
                            $no = 1;
                            foreach ($barangmasuk as $bm): ?>
                                <tr>
                                    <th scope="row"><?= $no++; ?></th>
                                    <td><?= $bm['id_barang_masuk']; ?></td>
                                    <td><?= $bm['nama_barang']; ?></td>
                                    <td><?= date('d-m-Y', strtotime($bm['tanggal_masuk'])); ?></td>
                                    <td><?= $bm['jumlah_masuk']; ?></td>
                                    <td><?= number_format($bm['harga_satuan'], 2, ',', '.'); ?></td>
                                    <td><?= number_format($bm['total_harga'], 2, ',', '.'); ?></td>
                                    <td>
                                        <a href="<?= base_url('barangmasuk/delete/' . $bm['id_barang_masuk']) ?>"
                                            class="btn btn-danger btn-circle btn-sm"
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?');"><i
                                                class="fa fa-trash"></i></a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="10" class="text-center">Data Kosong</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div> -->

<div class="row justify-content-center">
    <div class="col-lg-12">
        <div class="card shadow-sm border-bottom-primary">
            <div class="card-header bg-white py-3">
                <div class="row">
                    <div class="col">
                        <h4 class="h5 align-middle m-0 font-weight-bold text-primary">
                            Data Barang Masuk
                        </h4>
                    </div>
                    <div class="col-auto">
                        <a href="<?= base_url('barangmasuk/add') ?>" class="btn btn-sm btn-primary btn-icon-split">
                            <span class="icon">
                                <i class="fa fa-plus"></i>
                            </span>
                            <span class="text">
                                Tambah Data
                            </span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-hover" id="dataTable">
                    <thead>
                        <tr>
                            <th scope="col">NO</th>
                            <th scope="col">ID Barang Masuk</th>
                            <th scope="col">Nama Barang</th>
                            <th scope="col">Tanggal Masuk</th>
                            <th scope="col">Jumlah Masuk</th>
                            <th scope="col">Harga Satuan</th>
                            <th scope="col">Total Harga</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        foreach ($barangmasuk as $bm) : ?>
                            <tr>
                                <th scope="row"><?= $no++; ?></th>
                                <td><?= $bm['id_barang_masuk']; ?></td>
                                <td><?= $bm['nama_barang']; ?></td>
                                <td><?= date('d-m-Y', strtotime($bm['tanggal_masuk'])); ?></td>
                                <td><?= $bm['jumlah_masuk']; ?></td>
                                <td><?= number_format($bm['harga_satuan'], 2, ',', '.'); ?></td>
                                <td><?= number_format($bm['total_harga'], 2, ',', '.'); ?></td>
                                <td>
                                    <a href="<?= base_url('barangmasuk/delete/' . $bm['id_barang_masuk']) ?>" class="btn btn-danger btn-circle btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?');"><i class="fa fa-trash"></i></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
