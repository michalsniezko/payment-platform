<?php
declare(strict_types=1);

namespace App\Controllers;

class InvoiceController
{
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
        $amount = $_POST['amount'];
        echo 'POSTED: ' . $amount;
    }
}
