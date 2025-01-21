<div class="container-fluid">
        <div class="card card-default">
            <div class="card-header">
                <h3 class="card-title">Tambah Kontak</h3>
            </div>

            <div class="card-body">
                <form id="contactForm">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nama">Nama Kontak</label>
                                <input type="text" class="form-control" id="nama" name="nama" required>
                            </div>

                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>

                            <div class="form-group">
                                <label for="telepon">No. Telepon</label>
                                <input type="tel" class="form-control" id="telepon" name="telepon" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="wilayah">Wilayah</label>
                                <select class="form-control select2bs4" id="wilayah" name="wilayah" style="width: 100%;">
                                    <option value="">Pilih Wilayah</option>
                                    <option value="Kota Kediri">Kota Kediri</option>
                                    <option value="Kota Blitar">Kota Blitar</option>
                                    <option value="Kab. Tulungagung">Kabupaten Tulungagung</option>
                                    <option value="Kab. Blitar">Kabupaten Blitar</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="sekolah">Sekolah</label>
                                <select class="form-control select2bs4" id="sekolah" name="sekolah" style="width: 100%;" required>
                                    <option value="">Pilih Sekolah</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="alamat">Alamat</label>
                                <textarea class="form-control" id="alamat" name="alamat" rows="3"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary float-right">Simpan</button>
                            <button type="button" class="btn btn-secondary float-right mr-2">Batal</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>