<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Attributes\Get;
use App\Attributes\Post;
use App\Enum\InvoiceStatus;
use App\Models\Invoice;
use App\Services\InvoiceService;

readonly class InvoiceController
{
    public function __construct(
        private InvoiceService $invoiceService
    )
    {
    }

    #[Get('/invoices')]
    public function index(): View
    {
        $invoices = new Invoice()->all(InvoiceStatus::PAID);

        return View::make('invoices/index', ['invoices' => $invoices]);
    }

    #[Get('/invoices/create')]
    public function create(): View
    {
        return View::make('invoices/create');
    }

    #[Post('/invoices/create')]
    public function store(): void
    {
        $name = $_POST['name'];
        $amount = $_POST['amount'];

        $this->invoiceService->process(['name' => (string)$name], (float)$amount);
        header('location: /');
    }
}
