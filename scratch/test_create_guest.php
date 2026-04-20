<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Customer;

try {
    $guest = Customer::create([
        'customer_name' => 'Guest/Non-Member',
        'phone' => '0000000000',
        'address' => 'N/A'
    ]);
    echo "Guest created with ID: {$guest->id}\n";
} catch (\Exception $e) {
    echo "Error creating guest: " . $e->getMessage() . "\n";
}
