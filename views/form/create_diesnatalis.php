<form id="contactForm" action="config/create_kontak.php" method="POST">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="nama_sekolah">Nama Sekolah <span style="color: red;">*</span></label>
                <input type="text" class="form-control" id="nama_sekolah" name="nama_sekolah" required>
            </div>
            <div class="form-group">
                <label for="alamat">Alamat Sekolah <span style="color: red;">*</span></label>
                <input type="text" class="form-control" id="alamat" name="alamat" required>
            </div>
            <div class="form-group">
                <label for="nomor_kontak">Nomor Kontak (10–15 digit angka tanpa spasi) <span
                        style="color: red;">*</span></label>
                <input type="text" class="form-control" id="nomor_kontak" name="nomor_kontak" pattern="[0-9]{10,15}"
                    maxlength="15" required oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                <small class="form-text text-muted">Masukkan hanya angka, tanpa spasi atau simbol
                    (+, -, dll).</small>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="pemilik_kontak">Pemilik Kontak <span style="color: red;">*</span></label>
                <input type="text" class="form-control" id="pemilik_kontak" name="pemilik_kontak" required>
            </div>
            <div class="form-group">
                <label for="jabatan">Jabatan <span style="color: red;">*</span></label>
                <input type="text" class="form-control" id="jabatan" name="jabatan" required>
            </div>
            <div class="form-group">
                <label for="tanggal_dn">Tanggal Dies Natalis <span style="color: red;">*</span></label>
                <div class="d-flex">
                    <select class="form-control" id="dn_tanggal" style="width: 50%; margin-right: 5px;">
                        <option value="">Tanggal</option>
                        <!-- Options will be populated by JavaScript -->
                    </select>
                    <select class="form-control" id="dn_bulan" style="width: 50%;">
                        <option value="">Bulan</option>
                        <!-- Options will be populated by JavaScript -->
                    </select>
                    <!-- Hidden input untuk menyimpan nilai gabungan dalam format DD-MM -->
                    <input type="hidden" id="tanggal_dn" name="tanggal_dn">
                </div>
                <small class="form-text text-muted" id="display_tanggal_dn"></small>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12">
            <button type="submit" name="Submit" class="btn btn-primary float-right">Simpan</button>
            <button type="reset" class="btn btn-secondary float-right mr-2">Batal</button>
        </div>
    </div>
</form>