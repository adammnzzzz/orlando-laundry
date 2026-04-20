<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\TypeOfService;

$all = TypeOfService::all();
foreach ($all as $s) {
    echo "ID: {$s->id}, Name: {$s->service_name}, Price: {$s->price}\n";
}
