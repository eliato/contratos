<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Contract;
use App\Jobs\GenerateContractPdf;

$contracts = Contract::whereIn('status', ['paid', 'signed'])->get();
echo "Contracts found: " . $contracts->count() . PHP_EOL;
foreach ($contracts as $c) {
    GenerateContractPdf::dispatch($c);
    echo "Dispatched for contract #{$c->id}" . PHP_EOL;
}
