<?php
declare(strict_types=1);

namespace App;

use App\Contracts\PaymentGatewayInterface;
use App\Controllers\View;
use App\Exceptions\RouteNotFoundException;
use App\Services\PaddlePayment;
use Dotenv\Dotenv;
use ReflectionException;
use Symfony\Component\Mailer\MailerInterface;

class App
{
    private static DB $db;
    private Config $config;

    public function __construct(
        protected Container $container,
        protected ?Router $router = null,
        protected array   $request = [],
    )
    {
    }

    public static function db(): DB
    {
        return static::$db;
    }

    public function boot(): static
    {
        $dotenv = Dotenv::createImmutable(dirname(__DIR__));
        $dotenv->load();

        $this->config = new Config($_ENV);

        static::$db = new DB($this->config->db ?? []);

        // implicitly bind interface to implementing class:
        $this->container->set(PaymentGatewayInterface::class, fn(Container $container) => $container->get(PaddlePayment::class));
        $this->container->set(MailerInterface::class, fn() => new CustomMailer($this->config->mailer['dsn']));

        return $this;
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
