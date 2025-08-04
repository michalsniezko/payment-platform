<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Attributes\Get;
use App\Attributes\Post;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class UserController
{
    public function __construct(
        private MailerInterface $mailer,
    )
    {
    }

    #[Get('/users/create')]
    public function create(): View
    {
        return View::make('users/register');
    }

    /**
     * @throws TransportExceptionInterface
     */
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

        $email = new Email()
            ->from('support@example.com')
            ->to($email)
            ->subject('Welcome!')
            ->text($text)
            ->html($htmlBody);

        $this->mailer->send($email);
    }
}
