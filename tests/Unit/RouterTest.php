<?php
declare(strict_types=1);

namespace Tests\Unit;

use App\Container;
use App\Exceptions\RouteNotFoundException;
use App\Router;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\TestCase;
use Tests\DataProviders\RouterDataProvider;

class RouterTest extends TestCase
{
    private Router $router;

    public function testItRegistersARoute(): void
    {
        $this->router->register('get', '/users', ['Users', 'index']);

        $expected = ['get' => ['/users' => ['Users', 'index']]];
        $this->assertEquals($expected, $this->router->routes());
    }

    public function testItRegistersGetRoute(): void
    {
        $this->router->get('/users', ['Users', 'index']);

        $expected = ['get' => ['/users' => ['Users', 'index']]];
        $this->assertEquals($expected, $this->router->routes());
    }

    public function testItRegistersPostRoute(): void
    {
        $this->router->post('/users', ['Users', 'store']);

        $expected = ['post' => ['/users' => ['Users', 'store']]];
        $this->assertEquals($expected, $this->router->routes());
    }

    public function testThereAreNoRoutesWhenRouterIsCreated(): void
    {
        $this->router = new Router(new Container());

        $this->assertEmpty($this->router->routes());
    }

    #[DataProviderExternal(RouterDataProvider::class, 'routeNotFoundCases')]
    public function testItThrowsRouteNotFoundException(string $requestUri, string $requestMethod): void
    {
        $users = new class () {
            public function delete(): bool
            {
                return true;
            }
        };

        $this->router->post('/users', [$users::class, 'store']);
        $this->router->get('/users', ['Users', 'index']);

        $this->expectException(RouteNotFoundException::class);
        $this->router->resolve($requestUri, $requestMethod);
    }

    /**
     * @throws RouteNotFoundException
     */
    public function testItResolvesRouteFromClosure(): void
    {
        $this->router->get('/users', fn() => [1, 2, 3]);
        $this->assertEquals([1, 2, 3], $this->router->resolve('/users', 'get'));
    }

    /**
     * @throws RouteNotFoundException
     */
    public function testItResolvesRouteFromClass(): void
    {
        $users = new class () {
            public function index(): array
            {
                return [1, 2, 3];
            }
        };

        $this->router->get('/users', [$users, 'index']);
        $this->assertSame([1, 2, 3], $this->router->resolve('/users', 'get'));
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->router = new Router(new Container());
    }
}
