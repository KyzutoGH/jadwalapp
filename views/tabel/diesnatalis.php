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
        <?php
        require_once __DIR__ . '/data/data_diesnatalis.php'; ?>
    </div>
</div>

<?php
require_once __DIR__ . '/../../config/javscript/script_diesnatalis.php'; ?>