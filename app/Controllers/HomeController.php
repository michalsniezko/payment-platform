<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Services\InvoiceService;

class HomeController
{
    public function __construct(private InvoiceService $invoiceService)
    {

    }

    public function index(): View
    {
        $this->invoiceService->process([], 43.4);

        return View::make('index');
    }

    public function upload(): never
    {
        $filePath = STORAGE_PATH . '/' . $_FILES['receipt']['name'];

        move_uploaded_file($_FILES['receipt']['tmp_name'], $filePath);

        header('Location: /');
        exit;
    }

    public function download(): void
    {
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="receipt.pdf"');
        readfile(STORAGE_PATH . '/receipt.pdf');
    }
}
