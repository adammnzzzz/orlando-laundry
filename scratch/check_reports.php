<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\TransOrder;

$reports = TransOrder::where('order_status', 1)->get();
echo "Report Order Count: " . $reports->count() . "\n";
echo "Total Income: " . $reports->sum('grand_total') . "\n";
foreach ($reports as $r) {
    echo "ID: {$r->id}, Name: " . ($r->guest_name ?: ($r->customer ? $r->customer->customer_name : "NULL")) . ", GrandTotal: {$r->grand_total}\n";
}
