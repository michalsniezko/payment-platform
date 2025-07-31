<?php
declare(strict_types=1);

namespace App\Enum;

enum HttpMethod: string
{
    case POST = 'post';
    case GET = 'get';
    case PUT = 'put';
    case DELETE = 'delete';
    case PATCH = 'patch';
    case HEAD = 'head';
}
