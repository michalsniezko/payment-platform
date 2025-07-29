<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Attributes\Get;
use App\Attributes\Post;
use App\Attributes\Route;

readonly class HomeController
{
    #[Get(path: '/')]
    #[Get(path: '/home')]
    public function index(): View
    {
        return View::make('index');
    }

    #[Post(path: '/')]
    public function upload(): never
    {
        $filePath = STORAGE_PATH . '/' . $_FILES['receipt']['name'];

        move_uploaded_file($_FILES['receipt']['tmp_name'], $filePath);

        header('Location: /');
        exit;
    }

    #[Route(path: '/download', requestMethod: 'get')]
    public function download(): void
    {
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="receipt.pdf"');
        readfile(STORAGE_PATH . '/receipt.pdf');
    }
}
