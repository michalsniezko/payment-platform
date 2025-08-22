<?php

declare(strict_types=1);

use App\EloquentModels\Invoice;
use App\EloquentModels\InvoiceItem;
use App\Enum\InvoiceStatus;
use Carbon\Carbon;
use Illuminate\Database\Capsule\Manager as CapsuleManager;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../eloquent.php';

$invoiceId = 56;
Invoice::query()->where('id', $invoiceId)->update(['status' => InvoiceStatus::PAID]);

Invoice::query()->where('status', InvoiceStatus::PAID)->get()->each(function (Invoice $invoice) {
    echo $invoice->id . ', ' . $invoice->status->toString() . ', ' . $invoice->created_at->format('m/d/Y');
    echo PHP_EOL;

    $item = $invoice->items->first();
    $item->description = 'First item in this invoice';

    $invoice->push();
});

//CapsuleManager::connection()->transaction(function () {
//    $invoice = new Invoice();
//
//    $invoice->amount = 45;
//    $invoice->invoice_number = '155';
//    $invoice->status = InvoiceStatus::PENDING;
//    $invoice->due_date = new Carbon()->addDays(10);
//
//    $invoice->save();
//
//    $items = [
//        ['Item 1', 1, 15],
//        ['Item 2', 2, 7.5],
//        ['Item 3', 4, 3.75]
//    ];
//
//    foreach ($items as [$description, $quantity, $unitPrice]) {
//        $item = new InvoiceItem();
//        $item->description = $description;
//        $item->quantity = $quantity;
//        $item->unit_price = $unitPrice;
//
//        $item->invoice()->associate($invoice);
//
//        $item->save();
//    }
//});

