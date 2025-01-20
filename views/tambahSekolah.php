<h2>Tambah Sekolah Baru</h2>
<form id="add-school-form" method="post" action="tambahSekolah.php">
    <div>
        <label for="nama_sekolah">Nama Sekolah:</label>
        <input type="text" id="nama_sekolah" name="nama_sekolah" required>
    </div>
    <div>
        <label for="jenis_sekolah">Jenis Sekolah:</label>
        <select id="jenis_sekolah" name="jenis_sekolah" required>
            <option value="">Pilih Jenis Sekolah</option>
            <option value="SD">SD</option>
            <option value="SMP">SMP</option>
            <option value="SMA">SMA</option>
            <option value="SMK">SMK</option>
        </select>
    </div>
    <div>
        <label for="alamat">Alamat:</label>
        <textarea id="alamat" name="alamat" required></textarea>
    </div>
    <div>
        <button type="submit">Tambah Sekolah</button>
    </div>
</form>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_sekolah = $_POST['nama_sekolah'];
    $jenis_sekolah = $_POST['jenis_sekolah'];
    $alamat = $_POST['alamat'];

    $result = add_school($nama_sekolah, $jenis_sekolah, $alamat);

    if ($result) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
    exit;
}
?>
