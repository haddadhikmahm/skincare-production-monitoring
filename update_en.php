<?php

$en = json_decode(file_get_contents(__DIR__ . '/lang/en.json'), true);

$newMap = [
    'Form Pencatatan Insiden Baru' => 'New Incident Logging Form',
    'Riwayat Insiden Terakhir' => 'Recent Incident History',
    'Input Hasil Quality Control' => 'Input Quality Control Results',
    'Riwayat Quality Release' => 'Quality Release History',
    'Daftar Pengguna Sistem' => 'System Users List',
    'Ubah Password' => 'Change Password',
    'Mulai Proses Baru — Digital Timestamp' => 'Start New Process — Digital Timestamp',
    'Semua Notifikasi' => 'All Notifications',
];

foreach ($newMap as $k => $v) {
    $en[$k] = $v;
}
file_put_contents(__DIR__ . '/lang/en.json', json_encode($en, JSON_PRETTY_PRINT));
echo "lang/en.json updated.\n";
