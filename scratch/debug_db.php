<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\TransOrder;

$last5 = TransOrder::latest()->take(5)->get();
foreach ($last5 as $t) {
    echo "ID: {$t->id}, Code: {$t->order_code}, Status: {$t->order_status}, CustID: {$t->id_customer}, GuestName: {$t->guest_name}\n";
}
