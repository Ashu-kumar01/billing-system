<?php
require __DIR__.'/../vendor/autoload.php';
$app = require __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$tables = ['users','customers','products','categories','units','invoices','invoice_items','payments','expenses','expense_categories','suppliers','purchases','purchase_items','product_stocks','stock_movements','settings'];
foreach ($tables as $table) {
    echo "TABLE $table\n";
    $cols = Illuminate\Support\Facades\Schema::getColumnListing($table);
    foreach ($cols as $col) {
        echo $col, "\n";
    }
    echo "\n";
}
