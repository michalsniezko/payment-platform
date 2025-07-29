<?php
declare(strict_types=1);

namespace App\Attributes;

use App\Contracts\RouteInterface;
use Attribute;

#[Attribute]
class Route implements RouteInterface
{
    public function __construct(
        public string $path,
        public string $requestMethod = 'get'
    )
    {
    }
}
