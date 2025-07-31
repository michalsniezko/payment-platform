<?php
declare(strict_types=1);

namespace App;

use App\Contracts\PaymentGatewayInterface;
use App\Controllers\View;
use App\Exceptions\RouteNotFoundException;
use App\Services\PaddlePayment;
use ReflectionException;
use Symfony\Component\Mailer\MailerInterface;

class App
{
    private static DB $db;

    public function __construct(
        protected Container $container,
        protected Router $router,
        protected array  $request,
        protected Config    $config,
    )
    {
        static::$db = new DB($config->db ?? []);

        // implicitly bind interface to implementing class:
        $this->container->set(
            PaymentGatewayInterface::class,
            fn(Container $container) => $container->get(PaddlePayment::class)
        );
        $this->container->set(MailerInterface::class, fn() => new CustomMailer($config->mailer['dsn']));
    }

    public static function db(): DB
    {
        return static::$db;
    }

    public function run(): void
    {
        try {
            echo $this->router->resolve($this->request['uri'], strtolower($this->request['method']));
        } catch (RouteNotFoundException) {
            http_response_code(404);

            echo View::make('error/404');
        } catch (Exceptions\ContainerException|ReflectionException $e) {
            http_response_code(500);
            echo $e->getMessage();
        }
    }

}
