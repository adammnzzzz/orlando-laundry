<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Customer;

foreach ([54, 56] as $id) {
    $c = Customer::find($id);
    if ($c) {
        echo "ID: {$c->id}, Name: {$c->customer_name}, Phone: {$c->phone}\n";
    } else {
        echo "ID: {$id} not found\n";
    }
}
