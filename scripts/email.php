<?php

use App\App;
use App\Container;
use App\Exceptions\ContainerException;
use App\Services\EmailService;

require_once __DIR__ . '/../vendor/autoload.php';

$container = new Container();

new App($container)->boot();

try {
    $container->get(EmailService::class)->sendQueuedEmails();
} catch (ContainerException|ReflectionException $e) {
    echo $e->getMessage();
}
