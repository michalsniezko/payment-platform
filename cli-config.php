<?php

declare(strict_types=1);

require 'vendor/autoload.php';

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Doctrine\Migrations\Configuration\EntityManager\ExistingEntityManager;
use Doctrine\Migrations\DependencyFactory;
use Doctrine\Migrations\Configuration\Migration\PhpFile;
use Doctrine\DBAL\DriverManager;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$config = new PhpFile('migrations.php');

$params = [
    'host' => $_ENV['DB_HOST'],
    'user' => $_ENV['DB_USER'],
    'password' => $_ENV['DB_PASSWORD'],
    'dbname' => $_ENV['DB_DATABASE'],
    'driver' => $_ENV['DB_DRIVER'] ?? 'pdo_mysql'
];

$connection = DriverManager::getConnection($params);
$configuration = ORMSetup::createAttributeMetadataConfiguration([__DIR__ . '/app/Entity']);

$entityManager = new EntityManager($connection, $configuration);

return DependencyFactory::fromEntityManager($config, new ExistingEntityManager($entityManager));