<?php

$en = json_decode(file_get_contents(__DIR__ . '/lang/en.json'), true);

$phrases = [
    // Profile
    'Informasi Dasar' => 'Basic Information',
    'Nama Lengkap' => 'Full Name',
    'Alamat Email' => 'Email Address',
    'Foto Profil (Opsional)' => 'Profile Picture (Optional)',
    'Pilih gambar baru (JPG, PNG, GIF) max 2MB untuk mengganti foto saat ini.' => 'Choose a new image (JPG, PNG, GIF) max 2MB to replace current photo.',
    'Keamanan Akun' => 'Account Security',
    'Password Baru' => 'New Password',
    'Minimal 8 karakter' => 'Minimum 8 characters',
    'Biarkan kosong jika Anda tidak ingin mengubah password.' => 'Leave blank if you do not want to change the password.',
    'Konfirmasi Password Baru' => 'Confirm New Password',
    'Ketik ulang password baru' => 'Retype new password',

    // Notifications
    'Histori lengkap seluruh notifikasi dan kejadian dalam sistem' => 'Complete history of all notifications and events in the system',
    'Daftar Notifikasi' => 'Notifications List',
    'Judul Notifikasi' => 'Notification Title',
    'Keterangan / Deskripsi' => 'Details / Description',
    'Waktu (Relatif)' => 'Time (Relative)',
    'Tanggal Kejadian' => 'Event Date',

    // App Layout
    'Notifikasi' => 'Notifications',
    'Memuat...' => 'Loading...',
    'Profil Saya' => 'My Profile',
    'Pengaturan' => 'Settings',
    'Logout' => 'Logout',
    'Tandai dibaca' => 'Mark as read',
    'Lihat Semua Notifikasi &rarr;' => 'View All Notifications &rarr;',
];

foreach ($phrases as $id => $enStr) {
    $en[$id] = $enStr;
}

file_put_contents(__DIR__ . '/lang/en.json', json_encode($en, JSON_PRETTY_PRINT));
echo "lang/en.json updated.\n";

$files = [
    'resources/views/profile/index.blade.php',
    'resources/views/notifications/index.blade.php',
    'resources/views/layouts/app.blade.php',
];

foreach ($files as $file) {
    $path = __DIR__ . '/' . $file;
    if (file_exists($path)) {
        $content = file_get_contents($path);
        $modified = false;
        
        foreach (array_keys($phrases) as $str) {
            if (strpos($content, "{{ __('$str') }}") === false) {
                // Exact tags
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
                    // Regex for general padding, taking placeholder attributes into account
                    if (strpos($content, 'placeholder="' . $str . '"') !== false) {
                        $content = str_replace('placeholder="' . $str . '"', 'placeholder="{{ __(\'' . $str . '\') }}"', $content);
                        $modified = true;
                    } else {
                        $pattern = '/(>|\n)\s*' . preg_quote($str, '/') . '\s*(<|\n)/';
                        $content = preg_replace_callback($pattern, function($matches) use (&$modified, $str) {
                            $modified = true;
                            return $matches[1] . " {{ __('" . $str . "') }} " . $matches[2];
                        }, $content);
                    }
                }
            }
        }
        
        if ($modified) {
            file_put_contents($path, $content);
            echo "Modified: $file\n";
        }
    }
}
