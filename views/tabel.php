<?php

if ($menu == "Tabel") { ?>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Data Master</h3>
            <div class="float-right">
                <!-- Navigation links -->
                <a href="index.php?menu=Create&submenu=ContactAdd" class="btn btn-<?= ($submenu == 'Sekolah') ? 'primary' : 'secondary' ?>">
                    Tambah Contact Person
                </a>
                <a href="index.php?menu=Tabel&submenu=Sekolah" class="btn btn-<?= ($submenu == 'Sekolah') ? 'primary' : 'secondary' ?>">
                    Data Sekolah
                </a>
                <a href="index.php?menu=Tabel&submenu=Contact" class="btn btn-<?= ($submenu == 'Contact') ? 'primary' : 'secondary' ?>">
                    Data Contact Person
                </a>
            </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <?php
            // Pengkondisian konten berdasarkan submenu
            if ($submenu == 'Sekolah') {
                // Tabel Sekolah
            ?>
                <table id="tabelSekolah" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Sekolah</th>
                            <th>Alamat</th>
                            <th>Telepon</th>
                            <th>Email</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Contoh data sekolah (ganti dengan query database Anda)
                        $sekolah = [
                            ['id' => 1, 'nama' => 'SMA Negeri 1', 'alamat' => 'Jl. Pendidikan No. 1', 'telepon' => '021-1234567', 'email' => 'sman1@edu.com'],
                            ['id' => 2, 'nama' => 'SMK Negeri 1', 'alamat' => 'Jl. Kejuruan No. 2', 'telepon' => '021-7654321', 'email' => 'smkn1@edu.com']
                        ];

                        foreach ($sekolah as $index => $s) {
                            echo "<tr>";
                            echo "<td>" . ($index + 1) . "</td>";
                            echo "<td>{$s['nama']}</td>";
                            echo "<td>{$s['alamat']}</td>";
                            echo "<td>{$s['telepon']}</td>";
                            echo "<td>{$s['email']}</td>";
                            echo "<td>
                                    <button class='btn btn-sm btn-primary' onclick='editSekolah({$s['id']})'>Edit</button>
                                    <button class='btn btn-sm btn-danger' onclick='hapusSekolah({$s['id']})'>Hapus</button>
                                </td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>

            <?php
            } elseif ($submenu == 'Contact') {
                // Tabel Contact Person
            ?>
                <table id="tabelContact" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Sekolah</th>
                            <th>Kelas</th>
                            <th>Nama Contact Person</th>
                            <th>No. HP</th>
                            <th>Jabatan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Contoh data contact person (ganti dengan query database Anda)
                        $contacts = [
                            ['id' => 1, 'sekolah' => 'SMA Negeri 1', 'kelas' => 'X-1', 'nama' => 'Budi Santoso', 'hp' => '081234567890', 'jabatan' => 'Wali Kelas'],
                            ['id' => 2, 'sekolah' => 'SMK Negeri 1', 'kelas' => 'XI-2', 'nama' => 'Ani Wijaya', 'hp' => '087654321098', 'jabatan' => 'Guru BK']
                        ];

                        foreach ($contacts as $index => $c) {
                            echo "<tr>";
                            echo "<td>" . ($index + 1) . "</td>";
                            echo "<td>{$c['sekolah']}</td>";
                            echo "<td>{$c['kelas']}</td>";
                            echo "<td>{$c['nama']}</td>";
                            echo "<td>{$c['hp']}</td>";
                            echo "<td>{$c['jabatan']}</td>";
                            echo "<td>
                                    <button class='btn btn-sm btn-primary' onclick='editContact({$c['id']})'>Edit</button>
                                    <button class='btn btn-sm btn-danger' onclick='hapusContact({$c['id']})'>Hapus</button>
                                </td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>

            <?php
            } else {
                echo "<div class='alert alert-info'>Silahkan pilih menu di atas</div>";
            }
            ?>
        </div>
        <!-- /.card-body -->
    </div>

    <!-- DataTables initialization -->
    <script>
        $(document).ready(function() {
            <?php if ($submenu == 'Sekolah') { ?>
                $('#tabelSekolah').DataTable({
                    "paging": true,
                    "lengthChange": true,
                    "searching": true,
                    "ordering": true,
                    "info": true,
                    "autoWidth": false,
                    "responsive": true
                });
            <?php } elseif ($submenu == 'Contact') { ?>
                $('#tabelContact').DataTable({
                    "paging": true,
                    "lengthChange": true,
                    "searching": true,
                    "ordering": true,
                    "info": true,
                    "autoWidth": false,
                    "responsive": true
                });
            <?php } ?>
        });

        // Placeholder functions for CRUD operations
        function editSekolah(id) {
            // Implement edit sekolah functionality
            console.log('Edit sekolah dengan ID:', id);
        }

        function hapusSekolah(id) {
            // Implement delete sekolah functionality
            if(confirm('Apakah Anda yakin ingin menghapus data sekolah ini?')) {
                console.log('Hapus sekolah dengan ID:', id);
            }
        }

        function editContact(id) {
            // Implement edit contact functionality
            console.log('Edit contact dengan ID:', id);
        }

        function hapusContact(id) {
            // Implement delete contact functionality
            if(confirm('Apakah Anda yakin ingin menghapus data contact person ini?')) {
                console.log('Hapus contact dengan ID:', id);
            }
        }
    </script>
<?php } else { ?>
    <h1>Tidak Ada</h1>
<?php } ?>