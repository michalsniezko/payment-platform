<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Services\InvoiceService;

class InvoiceController
{
    public function __construct(
        private InvoiceService $invoiceService
    )
    {
    }

    public function index(): View
    {
        return View::make('invoices/index');
    }

    public function create(): View
    {
        return View::make('invoices/create');
    }

    public function store(): void
    {
        $name = $_POST['name'];
        $amount = $_POST['amount'];

        $this->invoiceService->process(['name' => $name], $amount);
    }
}
