<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Customer;
use App\Models\TransOrder;

$guest = Customer::where('phone', '0000000000')->first();
if ($guest) {
    echo "Guest ID: {$guest->id}\n";
    $guestOrders = TransOrder::where('id_customer', $guest->id)->get();
    echo "Guest Order Count: " . $guestOrders->count() . "\n";
    foreach ($guestOrders as $o) {
        echo "Order ID: {$o->id}, Code: {$o->order_code}, Status: {$o->order_status}, GuestName: {$o->guest_name}\n";
    }
} else {
    echo "Guest customer not found.\n";
}
