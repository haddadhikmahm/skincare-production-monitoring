<?php

$en = json_decode(file_get_contents(__DIR__ . '/lang/en.json'), true);

$phrases = [
    'Perbarui Data Profil' => 'Update Profile Data',
    'Kelola akun dan hak akses pengguna sistem' => 'Manage accounts and system user access rights',
    'Verifikasi mutu produk sebelum direlease ke tahap berikutnya' => 'Verify product quality before release to the next stage',
    'Quality Control & Quality Release' => 'Quality Control & Quality Release',
    'Waste & Downtime Log' => 'Waste & Downtime Log',
    'Laporkan material yang terbuang (waste) dan durasi mesin berhenti (downtime) untuk melacak inefisiensi produksi secara akurat.' => 'Report wasted materials and machine downtime to accurately track production inefficiencies.',
    'Total Insiden' => 'Total Incidents',
    'Tambah Work Order Baru (SPK)' => 'Add New Work Order (SPK)',
    'Edit Work Order (SPK)' => 'Edit Work Order (SPK)',
    'Log Proses Produksi — Real-time' => 'Production Process Log — Real-time',
];

foreach ($phrases as $id => $enStr) {
    $en[$id] = $enStr;
}

file_put_contents(__DIR__ . '/lang/en.json', json_encode($en, JSON_PRETTY_PRINT));
echo "lang/en.json updated.\n";

$files = [
    'resources/views/profile/index.blade.php',
    'resources/views/users/index.blade.php',
    'resources/views/qc/index.blade.php',
    'resources/views/waste/index.blade.php',
    'resources/views/work_orders/create.blade.php',
    'resources/views/work_orders/edit.blade.php',
];

foreach ($files as $file) {
    $path = __DIR__ . '/' . $file;
    if (file_exists($path)) {
        $content = file_get_contents($path);
        $modified = false;
        foreach (array_keys($phrases) as $str) {
            // Only replace exact strings not already wrapped in __()
            if (strpos($content, "{{ __('$str') }}") === false) {
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
                    // Try regex replacement for padded strings
                    $pattern = '/(>|\n)\s*' . preg_quote($str, '/') . '\s*(<|\n)/';
                    $content = preg_replace_callback($pattern, function($matches) use (&$modified, $str) {
                        $modified = true;
                        return $matches[1] . " {{ __('" . $str . "') }} " . $matches[2];
                    }, $content);
                }
            }
        }
        if ($modified) {
            file_put_contents($path, $content);
            echo "Modified: $file\n";
        }
    }
}
