<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\TransOrder;

$guestOrders = TransOrder::where('order_status', 1)->whereNotNull('guest_name')->get();
echo "Completed Guest Orders: " . $guestOrders->count() . "\n";
foreach ($guestOrders as $o) {
    echo "ID: {$o->id}, Code: {$o->order_code}, Name: {$o->guest_name}\n";
}
