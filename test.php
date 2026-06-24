<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$key = config('services.gemini.api_key');

$response = \Illuminate\Support\Facades\Http::withoutVerifying()
    ->get('https://generativelanguage.googleapis.com/v1beta/models?key=' . $key);

echo json_encode($response->json(), JSON_PRETTY_PRINT);
