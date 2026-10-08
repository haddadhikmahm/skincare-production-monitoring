<?php
$en = json_decode(file_get_contents(__DIR__ . '/lang/en.json'), true);

$phrases = [
    'Tidak ada notifikasi baru' => 'No new notifications',
    'Validasi Gagal:' => 'Validation Failed:',
    'Apakah Anda yakin ingin menghapus data ini?' => 'Are you sure you want to delete this data?',
    'Konfirmasi Hapus' => 'Confirm Deletion',
    'Ya, Hapus' => 'Yes, Delete',
    'Batal' => 'Cancel',
    // DataTables
    'Cari data...' => 'Search data...',
    'Tampilkan _MENU_ baris' => 'Show _MENU_ rows',
    'Menampilkan _START_–_END_ dari <strong>_TOTAL_</strong> data' => 'Showing _START_-_END_ of <strong>_TOTAL_</strong> data',
    'Tidak ada data tersedia' => 'No data available',
    '(difilter dari _MAX_ total)' => '(filtered from _MAX_ total)',
    'Belum ada data' => 'No Data Yet',
    'Data belum tersedia untuk saat ini.' => 'Data is not available at the moment.',
    'Data tidak ditemukan' => 'No data found',
];

foreach ($phrases as $id => $enStr) {
    $en[$id] = $enStr;
}

file_put_contents(__DIR__ . '/lang/en.json', json_encode($en, JSON_PRETTY_PRINT));
echo "lang/en.json updated.\n";

$path = __DIR__ . '/resources/views/layouts/app.blade.php';
$content = file_get_contents($path);
$modified = false;

// 1. DataTables translations
$content = str_replace('"Cari data..."', '"{{ __(\'Cari data...\') }}"', $content);
$content = str_replace('"Tampilkan _MENU_ baris"', '"{{ __(\'Tampilkan _MENU_ baris\') }}"', $content);
$content = str_replace('"Menampilkan _START_–_END_ dari <strong>_TOTAL_</strong> data"', '"{{ __(\'Menampilkan _START_–_END_ dari <strong>_TOTAL_</strong> data\') }}"', $content);
$content = str_replace('"Tidak ada data tersedia"', '"{{ __(\'Tidak ada data tersedia\') }}"', $content);
$content = str_replace('"(difilter dari _MAX_ total)"', '"{{ __(\'(difilter dari _MAX_ total)\') }}"', $content);
$content = str_replace('<strong>Belum ada data</strong>', '<strong>{{ __(\'Belum ada data\') }}</strong>', $content);
$content = str_replace('Data belum tersedia untuk saat ini.', '{{ __(\'Data belum tersedia untuk saat ini.\') }}', $content);
$content = str_replace('Data tidak ditemukan', '{{ __(\'Data tidak ditemukan\') }}', $content);

// 2. JS "Tidak ada notifikasi baru"
$content = str_replace('Tidak ada notifikasi baru', '{{ __(\'Tidak ada notifikasi baru\') }}', $content);

// 3. Alerts & SweetAlerts
$content = str_replace('Validasi Gagal:', '{{ __(\'Validasi Gagal:\') }}', $content);
$content = str_replace('\'Apakah Anda yakin ingin menghapus data ini?\'', '\'{{ __(\'Apakah Anda yakin ingin menghapus data ini?\') }}\'', $content);
$content = str_replace('\'Konfirmasi Hapus\'', '\'{{ __(\'Konfirmasi Hapus\') }}\'', $content);
$content = str_replace('\'Ya, Hapus\'', '\'{{ __(\'Ya, Hapus\') }}\'', $content);
$content = str_replace('\'Batal\'', '\'{{ __(\'Batal\') }}\'', $content);

file_put_contents($path, $content);
echo "Modified: app.blade.php\n";
