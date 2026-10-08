<?php

$envPath = __DIR__ . '/.env';
$envContent = file_get_contents($envPath);

// Ubah BROADCAST_CONNECTION=null menjadi BROADCAST_CONNECTION=reverb
if (preg_match('/BROADCAST_CONNECTION=(.*)/', $envContent)) {
    $envContent = preg_replace('/BROADCAST_CONNECTION=(.*)/', 'BROADCAST_CONNECTION=reverb', $envContent);
} else {
    $envContent .= "\nBROADCAST_CONNECTION=reverb";
}

$reverbConfig = <<<EOD

REVERB_APP_ID=804823
REVERB_APP_KEY=skincare_app_key
REVERB_APP_SECRET=skincare_app_secret
REVERB_HOST="localhost"
REVERB_PORT=8080
REVERB_SCHEME=http

VITE_REVERB_APP_KEY="\${REVERB_APP_KEY}"
VITE_REVERB_HOST="localhost"
VITE_REVERB_PORT=8080
VITE_REVERB_SCHEME=http
EOD;

if (strpos($envContent, 'REVERB_APP_ID') === false) {
    $envContent .= $reverbConfig;
}

file_put_contents($envPath, $envContent);
echo ".env updated for Reverb\n";
