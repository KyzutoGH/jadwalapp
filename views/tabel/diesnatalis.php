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
                        <td><a href="<?= "tel:" . htmlspecialchars($d['nomor']); ?>">
    <?= htmlspecialchars($d['nomor']); ?>
</a></td>
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
                            </div>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<script>
// Function to handle school deletion
function hapusSekolah(id) {
    if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
        document.getElementById('hapusSekolahForm_' + id).submit();
    }
}

// Wait for document to be fully loaded
document.addEventListener('DOMContentLoaded', function() {
    // Initialize datepickers for all modals when they're shown
    const modals = document.querySelectorAll('.modal');
    modals.forEach(modal => {
        modal.addEventListener('shown.bs.modal', function() {
            const modalId = this.id;
            const id = modalId.replace('modalEdit', '');
            setupDatePicker(id);
        });
    });
});

// Function to set up date picker for a specific modal
function setupDatePicker(id) {
    // Get elements by ID instead of class to ensure uniqueness
    const tanggalSelect = document.getElementById('dn_tanggal_' + id);
    const bulanSelect = document.getElementById('dn_bulan_' + id);
    const hiddenInput = document.getElementById('tanggal_dn_' + id);
    const displayText = document.getElementById('display_tanggal_dn_' + id);
    
    // Log for debugging
    console.log('Setting up date picker for ID:', id);
    console.log('Found tanggal select:', tanggalSelect !== null);
    console.log('Found bulan select:', bulanSelect !== null);
    
    // Skip if elements don't exist
    if (!tanggalSelect || !bulanSelect || !hiddenInput || !displayText) {
        console.error('Required elements not found for ID:', id);
        return;
    }
    
    // Data bulan dengan nama
    const dataBulan = [
        { key: '01', nama: 'Januari' },
        { key: '02', nama: 'Februari' },
        { key: '03', nama: 'Maret' },
        { key: '04', nama: 'April' },
        { key: '05', nama: 'Mei' },
        { key: '06', nama: 'Juni' },
        { key: '07', nama: 'Juli' },
        { key: '08', nama: 'Agustus' },
        { key: '09', nama: 'September' },
        { key: '10', nama: 'Oktober' },
        { key: '11', nama: 'November' },
        { key: '12', nama: 'Desember' }
    ];
    
    // Function to update the hidden input and display text
    function updateDateValue() {
        const tanggal = tanggalSelect.value;
        const bulan = bulanSelect.value;
        
        if (tanggal && bulan) {
            // Update hidden input with DD-MM format
            hiddenInput.value = `${tanggal}-${bulan}`;
            
            // Find the month name
            const bulanObj = dataBulan.find(b => b.key === bulan);
            const bulanNama = bulanObj ? bulanObj.nama : '';
            
            // Update display text
            displayText.textContent = `Dipilih: ${tanggal} ${bulanNama}`;
        } else {
            hiddenInput.value = '';
            displayText.textContent = '';
        }
    }
    
    // Add event listeners
    tanggalSelect.addEventListener('change', updateDateValue);
    bulanSelect.addEventListener('change', updateDateValue);
    
    // Form validation
    const form = hiddenInput.closest('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            // Check if date is selected
            if (!hiddenInput.value) {
                e.preventDefault();
                e.stopPropagation();
                alert('Silakan pilih tanggal Dies Natalis!');
                
                // Highlight fields
                if (!tanggalSelect.value) tanggalSelect.classList.add('is-invalid');
                if (!bulanSelect.value) bulanSelect.classList.add('is-invalid');
            }
        });
        
        // Remove invalid class on change
        tanggalSelect.addEventListener('change', function() {
            this.classList.remove('is-invalid');
        });
        
        bulanSelect.addEventListener('change', function() {
            this.classList.remove('is-invalid');
        });
    }
    
    // If there's already a value, update the display
    if (hiddenInput.value) {
        // Make sure the selects have the right values
        updateDateValue();
    }
}
</script>