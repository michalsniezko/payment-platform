<?php
declare(strict_types=1);

namespace App\Controllers;

use App\App;
use App\Models\Invoice;
use App\Models\SignUp;
use App\Models\User;

class HomeController
{
    /**
     * @throws \Throwable
     */
    public function index(): View
    {
        $email = 'hrthtr@doe.com';
        $name = 'John Doe';
        $amount = 25;

        $userModel = new User();
        $invoiceModel = new Invoice();

        $invoiceId = new SignUp($userModel, $invoiceModel)->register(
            [
                'email' => $email,
                'name' => $name,
            ],
            [
                'amount' => $amount,
            ]
        );

        return View::make('index', ['invoice' => $invoiceModel->find($invoiceId)]);
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
