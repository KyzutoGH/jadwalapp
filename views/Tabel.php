<?php
$jadwalController = new JadwalController($database);
$jadwalData = $jadwalController->read();
?>
<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>Nama Sekolah</th>
                <th>Jenis</th>
                <th>Tanggal Dies</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($jadwalData as $jadwal): ?>
            <tr>
                <td><?php echo $jadwal['nama_sekolah']; ?></td>
                <td><?php echo $jadwal['jenis_sekolah']; ?></td>
                <td><?php echo $jadwal['tanggal_dies']; ?></td>
                <td>
                    <a href="?page=edit&id=<?php echo $jadwal['id']; ?>">Edit</a>
                    <a href="?page=delete&id=<?php echo $jadwal['id']; ?>" onclick="return confirm('Yakin hapus?')">Hapus</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

