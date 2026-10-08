<?php

$en = json_decode(file_get_contents(__DIR__ . '/lang/en.json'), true);

$phrases = [
    'Mulai Produksi' => 'Start Production',
    'Selesai' => 'Completed',
    'Simpan Laporan' => 'Save Report',
    'Simpan Hasil QC' => 'Save QC Result',
    'Simpan Perubahan' => 'Save Changes',
    'Selesaikan Work Order' => 'Complete Work Order',
    'Batal' => 'Cancel',
    'Simpan Data' => 'Save Data',
    'Update Status' => 'Update Status',
    'Lihat' => 'View',
    'Tandai dibaca' => 'Mark as read',
    'Lihat Semua Notifikasi &rarr;' => 'View All Notifications &rarr;',
    'Tutup' => 'Close',
    'Kembali' => 'Back',
    'Cetak Laporan' => 'Print Report',
];

foreach ($phrases as $id => $enStr) {
    $en[$id] = $enStr;
}

file_put_contents(__DIR__ . '/lang/en.json', json_encode($en, JSON_PRETTY_PRINT));
echo "lang/en.json updated.\n";

$dir = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(__DIR__ . '/resources/views'));

foreach ($dir as $file) {
    if ($file->isFile() && str_ends_with($file->getFilename(), '.blade.php')) {
        $path = $file->getPathname();
        $content = file_get_contents($path);
        $modified = false;
        
        foreach (array_keys($phrases) as $str) {
            // Only replace exact strings not already wrapped in __()
            if (strpos($content, "{{ __('$str') }}") === false) {
                // For button contents that might have icons before them:
                // > Selesai
                // </i> Selesai
                // </span> Selesai
                
                $patterns = [
                    '/(>|\n)\s*' . preg_quote($str, '/') . '\s*(<|\n)/', // general exact match
                    '/<\/i>\s*' . preg_quote($str, '/') . '\s*</', // match after icon
                    '/<\/i>\s*' . preg_quote($str, '/') . '\s*\n/', // match after icon end of line
                    '/<\/i>\s*' . preg_quote($str, '/') . '\s*$/m',
                ];

                foreach ($patterns as $pattern) {
                    $newContent = preg_replace_callback($pattern, function($matches) use ($str) {
                        // Keep the surrounding tags/newlines
                        $prefix = $matches[1] ?? '</i> ';
                        $suffix = $matches[2] ?? '<';
                        
                        if (strpos($matches[0], '</i>') !== false) {
                            $prefix = '</i> ';
                            $suffix = substr($matches[0], -1) === '<' ? '<' : (substr($matches[0], -1) === "\n" ? "\n" : "");
                        }

                        return $prefix . "{{ __('" . $str . "') }}" . $suffix;
                    }, $content);

                    if ($newContent !== $content) {
                        $content = $newContent;
                        $modified = true;
                    }
                }
                
                // Specific text replacements without regex for safety if they are exactly matched
                if (strpos($content, ">$str<") !== false) {
                    $content = str_replace(">$str<", ">{{ __('$str') }}<", $content);
                    $modified = true;
                }
            }
        }
        
        if ($modified) {
            file_put_contents($path, $content);
            echo "Modified: " . $file->getFilename() . "\n";
        }
    }
}
