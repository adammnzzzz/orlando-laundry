<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Customer;
use App\Models\TransOrder;

try {
    $guest = Customer::where('phone', '0000000000')->first();
    if (!$guest) die("Guest not found\n");

    $order = TransOrder::create([
        'id_customer' => $guest->id,
        'guest_name' => 'Test Guest',
        'guest_phone' => '123456',
        'order_code' => 'TEST-' . time(),
        'order_date' => now()->toDateString(),
        'order_status' => 0,
        'total' => 10000,
        'tax' => 1100,
        'discount' => 0,
        'grand_total' => 11100,
        'order_pay' => 20000,
        'order_change' => 8900
    ]);
    echo "Order created with ID: {$order->id}\n";
} catch (\Exception $e) {
    echo "Error creating order: " . $e->getMessage() . "\n";
}
