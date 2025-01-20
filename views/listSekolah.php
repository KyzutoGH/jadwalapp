<h2>Daftar Sekolah</h2>
<table>
    <thead>
        <tr>
            <th>Nama Sekolah</th>
            <th>Jenis Sekolah</th>
            <th>Alamat</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $schools = get_schools();
        foreach ($schools as $school) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($school['nama_sekolah']) . "</td>";
            echo "<td>" . htmlspecialchars($school['jenis_sekolah']) . "</td>";
            echo "<td>" . htmlspecialchars($school['alamat']) . "</td>";
            echo "</tr>";
        }
        ?>
    </tbody>
</table>
