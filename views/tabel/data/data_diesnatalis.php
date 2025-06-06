        <table id="tabelSekolah" class="tabelBarang table table-bordered table-striped" width="100%">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Sekolah</th>
                    <th>Alamat Sekolah</th>
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
                if (!$data) {
                    die('Database query error: ' . mysqli_error($db));
                }
                while ($d = mysqli_fetch_array($data)) {
                    ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($d['nama_sekolah']) ?></td>
                        <td><?= htmlspecialchars($d['alamat']) ?></td>
                        <td>
  <a href="<?= "https://wa.me/62" . ltrim($d['nomor'], '0'); ?>" target="_blank">
    <?= htmlspecialchars($d['nomor']); ?>
  </a>
</td>
                        <td><?= htmlspecialchars($d['pemilik_kontak']) ?></td>
                        <td><?= htmlspecialchars($d['jabatan']) ?></td>
                        <td>
                            <?php
                            $date = $d['tanggal_dn']; // Format: DD-MM
                            $day = date('d', strtotime($date));
                            $month = date('m', strtotime($date));
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
                            $month_string = isset($months[$month]) ? $months[$month] : '';
                            echo htmlspecialchars("$day $month_string");
                            ?>
                        </td>
                        <td><span class="badge <?php if ($d['status'] == 0) {
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
                                                                <label>Tanggal Dies Natalis (Format: DD-MM)</label>
                                                                <div class="d-flex">
                                                                    <select class="form-control tanggal-select" id="dn_tanggal_<?= $d['id'] ?>"
                                                                        style="width: 50%; margin-right: 5px;">
                                                                        <option value="">Tanggal</option>
                                                                        <?php for($i=1; $i<=31; $i++) { 
                                                                            $dayValue = str_pad($i, 2, '0', STR_PAD_LEFT);
                                                                            $selected = ($day == $dayValue) ? 'selected' : '';
                                                                            echo "<option value=\"{$dayValue}\" {$selected}>{$i}</option>";
                                                                        } ?>
                                                                    </select>
                                                                    <select class="form-control bulan-select" id="dn_bulan_<?= $d['id'] ?>"
                                                                        style="width: 50%;">
                                                                        <option value="">Bulan</option>
                                                                        <?php foreach($months as $key => $value) {
                                                                            $selected = ($month == $key) ? 'selected' : '';
                                                                            echo "<option value=\"{$key}\" {$selected}>{$value}</option>";
                                                                        } ?>
                                                                    </select>
                                                                    <!-- Hidden input to store the combined value in DD-MM format -->
                                                                    <input type="hidden" id="tanggal_dn_<?= $d['id'] ?>"
                                                                        name="tanggal_dn"
                                                                        value="<?= htmlspecialchars($d['tanggal_dn']) ?>">
                                                                </div>
                                                                <small id="display_tanggal_dn_<?= $d['id'] ?>" class="form-text text-muted">
                                                                    <?php if(!empty($d['tanggal_dn'])) {
                                                                        echo "Dipilih: $day $month_string";
                                                                    } ?>
                                                                </small>
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
                                <form id="hapusSekolahForm_<?= $d['id'] ?>" action="./config/hapus_kontak.php" method="POST"
                                    style="display:none;">
                                    <input type="hidden" name="id" value="<?= $d['id'] ?>">
                                </form>
                                <button class="btn btn-sm btn-danger" onclick="hapusSekolah(<?= $d['id'] ?>)" title="Hapus">
                                    <i class="far fa-trash-alt"></i>
                                </button>
                                <!-- Modal Konfirmasi -->
<div class="modal fade" id="modalHapus" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title">Konfirmasi Hapus</h5>
<button type="button" class="close" data-dismiss="modal" aria-label="Close">
  <span aria-hidden="true">&times;</span>
</button>
      </div>
      <div class="modal-body">
        Yakin ingin menghapus data ini?
      </div>
      <div class="modal-footer">
        <form id="formHapusFinal" method="POST" action="./config/hapus_kontak.php">
          <input type="hidden" name="id" id="hapusID">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-danger">Hapus</button>
        </form>
      </div>
    </div>
  </div>
</div>
                            </div>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>