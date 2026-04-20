<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\TransOrder;

$orders = TransOrder::where('order_status', 0)->get();
echo "Status 0 Orders: " . $orders->count() . "\n";
foreach ($orders as $o) {
    echo "ID: {$o->id}, CustID: {$o->id_customer}, Name: {$o->guest_name}, Customer: " . ($o->customer ? $o->customer->customer_name : "NULL") . "\n";
}
