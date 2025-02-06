<div class="card">
    <div class="card-header">
        <h3 class="card-title">Data Dies Natalis Sekolah Blitar dan Sekitarnya</h3>
        <div class="float-right">
            <a href="index.php?menu=Create&submenu=ContactAdd"
                class="btn btn-<?= ($submenu == 'ContactAdd') ? 'primary' : 'secondary' ?>">
                Tambah Data
            </a>
        </div>
    </div>
    <div class="card-body">
        <table id="tabelSekolah" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Sekolah</th>
                    <th>Alamat</th>
                    <th>Kontak</th>
                    <th>Pemilik Kontak</th>
                    <th>Jabatan</th>
                    <th>Tanggal DN</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                $data = mysqli_query($db, "select * from datadn");
                while ($d = mysqli_fetch_array($data)) {
                    ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($d['nama_sekolah']) ?></td>
                        <td><?= htmlspecialchars($d['alamat']) ?></td>
                        <td><a href="<?php if ($d['jenis'] == "Whatsapp") {
                            echo "tel:" . $d['nomor'];
                        } else if ($d['jenis'] == "Instagram") {
                            echo "https://www.instagram.com/" . $d['nomor'] . "/";
                        } else {
                            echo "fa-question-circle";
                        } ?>"><?= htmlspecialchars($d['nomor']) ?></a></td>
                        <td><?= htmlspecialchars($d['pemilik_kontak']) ?></a></td>
                        <td><?= htmlspecialchars($d['jabatan']) ?></a></td>
                        <td>
                            <?php
                            $date = $d['tanggal_dn']; // Format: DD-MM
                            list($day, $month) = explode('-', $date); // Memisahkan hari dan bulan
                            $months = [
                                '01' => 'Januari',
                                '02' => 'Februari',
                                '03' => 'Maret',
                                '04' => 'April',
                                '05' => 'Mei',
                                '06' => 'Juni',
                                '07' => 'Juli',
                                '08' => 'Agustus',
                                '09' => 'September',
                                '10' => 'Oktober',
                                '11' => 'November',
                                '12' => 'Desember'
                            ];
                            $month_string = $months[$month];
                            echo htmlspecialchars("$day $month_string");
                            ?>
                        </td>
                        <td><span class=" badge <?php if ($d['status'] == 0) {
                            echo "badge-success";
                        } else {
                            echo "badge-danger";
                        } ?>"><?php if ($d['status'] == 0) {
                             echo "Kontak Aktif";
                         } else if ($d['status'] == 1) {
                             echo "Kontak Belum Dihubungi";
                         } else {
                             echo "Kontak Tidak Aktif";
                         } ?></span></td>
                        <td class="text-center">
                            <div class="btn-group" role="group" aria-label="Actions">
                                <button class="btn btn-sm btn-primary" data-toggle="modal"
                                    data-target="#modalEdit<?= $d['id'] ?>">
                                    <i class="far fa-edit"></i>
                                </button>

                                <div class="modal fade" id="modalEdit<?= $d['id'] ?>" tabindex="-1" role="dialog">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Data</h5>
                                                <button type="button" class="close" data-dismiss="modal">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form action="config/edit_kontak.php" method="POST">
                                                <div class="modal-body">
                                                    <input type="hidden" name="id" value="<?= $d['id'] ?>">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Nama Sekolah</label>
                                                                <input type="text" class="form-control" name="nama_sekolah"
                                                                    value="<?= htmlspecialchars($d['nama_sekolah']) ?>"
                                                                    required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Alamat</label>
                                                                <input type="text" class="form-control" name="alamat"
                                                                    value="<?= htmlspecialchars($d['alamat']) ?>" required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Nomor Kontak</label>
                                                                <input type="text" class="form-control" name="nomor"
                                                                    value="<?= htmlspecialchars($d['nomor']) ?>" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Pemilik Kontak</label>
                                                                <input type="text" class="form-control"
                                                                    name="pemilik_kontak"
                                                                    value="<?= htmlspecialchars($d['pemilik_kontak']) ?>"
                                                                    required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Jabatan</label>
                                                                <input type="text" class="form-control" name="jabatan"
                                                                    value="<?= htmlspecialchars($d['jabatan']) ?>" required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Tanggal Dies Natalis (DD-MM)</label>
                                                                <input type="text" class="form-control" name="tanggal_dn"
                                                                    value="<?= htmlspecialchars($d['tanggal_dn']) ?>"
                                                                    required placeholder="DD-MM">
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Status Kontak</label>
                                                                <select class="form-control" name="status">
                                                                    <option value="0" <?= $d['status'] == 0 ? 'selected' : '' ?>>Kontak Aktif</option>
                                                                    <option value="1" <?= $d['status'] == 1 ? 'selected' : '' ?>>Kontak Belum Dihubungi
                                                                    </option>
                                                                    <option value="2" <?= $d['status'] == 2 ? 'selected' : '' ?>>Kontak Tidak Aktif
                                                                    </option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <form id="hapusSekolahForm" action="./config/hapus_kontak.php" method="POST"
                                    style="display:none;">
                                    <input type="hidden" name="id" value="<?= $d['id'] ?>">
                                </form>
                                <button class="btn btn-sm btn-danger" onclick="hapusSekolah(<?= $d['id'] ?>)" title="Hapus">
                                    <i class="far fa-trash-alt"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>