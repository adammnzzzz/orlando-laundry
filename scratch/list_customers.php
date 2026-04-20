<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Customer;

$all = Customer::all();
foreach ($all as $c) {
    echo "ID: {$c->id}, Name: {$c->customer_name}, Phone: {$c->phone}\n";
}
