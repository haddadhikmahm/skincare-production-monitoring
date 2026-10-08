<?php

$files = [
    'resources/views/users/index.blade.php',
    'resources/views/qc/index.blade.php',
    'resources/views/profile/index.blade.php',
    'resources/views/layouts/app.blade.php',
];

foreach ($files as $file) {
    $path = __DIR__ . '/' . $file;
    if (file_exists($path)) {
        $content = file_get_contents($path);
        
        $newContent = preg_replace('/urlencode\((.+?)\)/', 'urlencode(preg_replace(\'/[^a-zA-Z0-9\s]/\', \'\', $1))', $content);
        
        if ($newContent !== $content) {
            file_put_contents($path, $newContent);
            echo "Modified: $file\n";
        }
    }
}
