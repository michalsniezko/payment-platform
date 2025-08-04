<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Attributes\Get;
use App\Attributes\Post;
use App\Models\Email;
use Symfony\Component\Mime\Address;

class UserController
{

    #[Get('/users/create')]
    public function create(): View
    {
        return View::make('users/register');
    }

    #[Post('/users')]
    public function register(): void
    {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $first_name = explode(' ', $name)[0];

        $text = <<<Body
Hello $first_name,

Thank you for signing up!
Body;

        $htmlBody = <<<HTMLBody
<h1 style="text-align: center; color: blue">Welcome</h1>
Hello $first_name,
<br />
Thank you for signing up!
HTMLBody;

        new Email()->queue(
            new Address($email),
            new Address('support@example.com', 'Support'),
            'Welcome!',
            $htmlBody,
            $text
        );
    }
}
