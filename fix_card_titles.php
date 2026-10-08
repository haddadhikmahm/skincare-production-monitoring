<?php

$en = json_decode(file_get_contents(__DIR__ . '/lang/en.json'), true);

$phrases = [
    'Monitor Proses Produksi' => 'Production Process Monitor',
    'Catat dan pantau proses produksi secara real-time' => 'Record and monitor production processes in real-time',
    'Mulai Proses Baru — Digital Timestamp' => 'Start New Process — Digital Timestamp',
    'Log Proses Produksi — Real-time' => 'Production Process Log — Real-time',
    'Form Input Waste & Downtime' => 'Waste & Downtime Input Form',
    'Riwayat Waste & Downtime Produksi' => 'Production Waste & Downtime History',
    'Daftar Pengguna Sistem' => 'System Users List',
    'Form Input Quality Control' => 'Quality Control Input Form',
    'Riwayat QC Release' => 'QC Release History',
    'Semua Notifikasi' => 'All Notifications',
    'Ubah Password' => 'Change Password',
    'Quick Action' => 'Quick Action',
];

foreach ($phrases as $id => $enStr) {
    $en[$id] = $enStr;
}

file_put_contents(__DIR__ . '/lang/en.json', json_encode($en, JSON_PRETTY_PRINT));
echo "lang/en.json updated.\n";

$files = [
    'resources/views/produksi/index.blade.php',
    'resources/views/waste/index.blade.php',
    'resources/views/users/index.blade.php',
    'resources/views/qc/index.blade.php',
    'resources/views/notifications/index.blade.php',
    'resources/views/profile/index.blade.php',
    'resources/views/dashboard.blade.php',
];

foreach ($files as $file) {
    $path = __DIR__ . '/' . $file;
    if (file_exists($path)) {
        $content = file_get_contents($path);
        $modified = false;
        foreach (array_keys($phrases) as $str) {
            if (strpos($content, ">$str<") !== false) {
                $content = str_replace(">$str<", ">{{ __('$str') }}<", $content);
                $modified = true;
            } elseif (strpos($content, "$str\n") !== false) {
                $content = str_replace("$str\n", "{{ __('$str') }}\n", $content);
                $modified = true;
            } elseif (strpos($content, "$str</") !== false) {
                $content = str_replace("$str</", "{{ __('$str') }}</", $content);
                $modified = true;
            } else {
                // regex match just the exact string surrounded by non-word boundaries
                // this handles spaces and indents
                $pattern = '/(>|\n)\s*' . preg_quote($str, '/') . '\s*(<|\n)/';
                $content = preg_replace_callback($pattern, function($matches) use (&$modified, $str) {
                    $modified = true;
                    return $matches[1] . " {{ __('" . $str . "') }} " . $matches[2];
                }, $content);
            }
        }
        if ($modified) {
            file_put_contents($path, $content);
            echo "Modified: $file\n";
        }
    }
}
