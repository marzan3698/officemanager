<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$token = '1234567890123456789';
$mobile = '01700000000';
$message = 'Test SMS';

$response = \Illuminate\Support\Facades\Http::get('https://api.bdbulksms.net/api.php', [
    'token' => $token,
    'balance' => ''
]);

echo "Status: " . $response->status() . "\n";
echo "Body: " . $response->body() . "\n";
