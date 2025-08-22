<?php

declare(strict_types=1);

use App\Entity\Invoice;
use App\Entity\InvoiceItem;
use App\Enum\InvoiceStatus;
use Doctrine\Common\Collections\Order;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$params = [
    'host' => $_ENV['DB_HOST'],
    'user' => $_ENV['DB_USER'],
    'password' => $_ENV['DB_PASSWORD'],
    'dbname' => $_ENV['DB_DATABASE'],
    'driver' => $_ENV['DB_DRIVER'] ?? 'pdo_mysql'
];

$connection = DriverManager::getConnection($params);
$configuration = ORMSetup::createAttributeMetadataConfiguration([__DIR__ . '/Entity']);

$entityManager = new EntityManager($connection, $configuration);

$queryBuilder = $entityManager->createQueryBuilder();

// WHERE amount > :amount AND (status = :status OR created_at >= :date)
$query = $queryBuilder
    ->select('i', 'it')
    ->from(Invoice::class, 'i')
    ->join('i.items', 'it')
    ->where(
        $queryBuilder->expr()->andX(
            $queryBuilder->expr()->gt('i.amount', ':amount'),
            $queryBuilder->expr()->orX(
                $queryBuilder->expr()->eq('i.status', ':status'),
                $queryBuilder->expr()->gte('i.createdAt', ':date'),
            )
        )
    )
    ->setParameter('amount', 100)
    ->setParameter('status', InvoiceStatus::PAID->value)
    ->setParameter('date', '2022-03-25 00:00:00')
    ->addOrderBy('i.createdAt', Order::Descending->value)
    ->getQuery();

$invoices = $query->getResult();
/** @var Invoice $invoice */
foreach ($invoices as $invoice) {
    echo 'INVOICE:' . PHP_EOL;
    echo $invoice->getCreatedAt()->format('m/d/Y g:ia') . ', '
        . $invoice->getAmount() . ', '
        . $invoice->getStatus()->toString() . PHP_EOL;

    /** @var InvoiceItem $item */
    foreach ($invoice->getItems() as $item)
    {
        echo ' - ' . $item->getDescription()
            . ', ' . $item->getQuantity()
            . ', ' . $item->getUnitPrice() . PHP_EOL;
    }

    echo PHP_EOL;
}


//$query = $queryBuilder
//    ->select('i')
//    ->from(Invoice::class, 'i')
//    ->andWhere('i.amount > :amount')
//    ->setParameter('amount', 20)
//    ->addOrderBy('i.createdAt', Order::Descending->value)
//    ->getQuery();
//
//$invoices = $query->getArrayResult();
//
//var_dump($invoices);




///** @var Invoice $invoice */
//foreach ($invoices as $invoice) {
//    echo $invoice->getCreatedAt()->format('m/d/Y g:ia') . ', '
//        . $invoice->getAmount() . ', '
//        . $invoice->getStatus()->toString();
//
//    echo PHP_EOL;
//}




//$items = [
//    ['Item 1', 1, 15],
//    ['Item 2', 2, 7.5],
//    ['Item 3', 4, 3.75]
//];
//
//$invoice = new Invoice()
//    ->setAmount(45)
//    ->setInvoiceNumber('1')
//    ->setStatus(InvoiceStatus::PENDING)
//    ->setCreatedAt(new DateTime());
//
//foreach ($items as [$description, $quantity, $unitPrice]) {
//    $item = new InvoiceItem()
//        ->setDescription($description)
//        ->setQuantity($quantity)
//        ->setUnitPrice($unitPrice);
//
//    $invoice->addItem($item);
//}
//
//$entityManager->persist($invoice);
//
//$entityManager->flush();