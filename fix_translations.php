<?php

$en = json_decode(file_get_contents(__DIR__ . '/lang/en.json'), true);

$newMap = [
    'Pengaturan Sistem (Settings)' => 'System Settings',
    'Preferensi Pengguna' => 'User Preferences',
    'Bahasa Indonesia (ID)' => 'Indonesian (ID)',
    'English (EN)' => 'English (EN)',
    'Terang (Light Mode)' => 'Light Mode',
    'Gelap (Dark Mode)' => 'Dark Mode',
    'Profil Perusahaan (Admin)' => 'Company Profile (Admin)',
    'Nama Perusahaan' => 'Company Name',
    'Email Kontak Sistem' => 'System Contact Email',
    'Konfigurasi Batas Toleransi Produksi (Admin)' => 'Production Tolerance Configuration (Admin)',
    'Batas Kritis Waste (%)' => 'Critical Waste Limit (%)',
    'Target Minimal OEE (%)' => 'Minimum OEE Target (%)',
    'Jika persentase Waste melewati batas 5%, sistem akan mengirimkan notifikasi peringatan berlabel merah kepada Supervisor.' => 'If the Waste percentage exceeds the 5% limit, the system will send a red warning notification to the Supervisor.',
    'Simpan Pengaturan' => 'Save Settings',
    // From app.blade.php routes
    'Dashboard' => 'Dashboard',
    'Work Order' => 'Work Order',
    'Produksi' => 'Production',
    'QC / Quality Release' => 'QC / Quality Release',
    'Waste & Downtime' => 'Waste & Downtime',
    'Analisis VSM' => 'VSM Analysis',
    'Laporan' => 'Report',
    'Manajemen Pengguna' => 'User Management',
    'Pengaturan' => 'Settings',
    'Semua Notifikasi' => 'All Notifications',
];

foreach ($newMap as $k => $v) {
    $en[$k] = $v;
}
file_put_contents(__DIR__ . '/lang/en.json', json_encode($en, JSON_PRETTY_PRINT));
echo "lang/en.json updated.\n";
