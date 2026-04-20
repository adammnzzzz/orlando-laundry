<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Http\Controllers\TransactionController;
use Illuminate\Http\Request;
use App\Models\User;

$user = User::where('id_level', 1)->first();
auth()->login($user);

$request = Request::create('/transactions', 'POST', [
    'is_guest' => 1,
    'customer_name' => 'Real Guest Test',
    'phone' => '08111111',
    'order_pay' => 50000,
    'services' => [
        ['id' => 1, 'qty' => 2]
    ]
]);

$controller = app(TransactionController::class);
try {
    $response = $controller->store($request);
    echo "Response status: " . $response->getStatusCode() . "\n";
    if ($response->isRedirect()) {
        echo "Redirected to: " . $response->getTargetUrl() . "\n";
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
