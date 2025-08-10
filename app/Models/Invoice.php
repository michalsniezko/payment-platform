<?php
declare(strict_types=1);

namespace App\Models;

use App\Enum\InvoiceStatus;
use App\Model;

class Invoice extends Model
{
    public function all(InvoiceStatus $status): array
    {
        return $this->db
            ->createQueryBuilder()
            ->select('id', 'amount', 'status')
            ->from('invoices')
            ->where('status = :status')
            ->setParameter('status', $status->value)
            ->fetchAllAssociative();
    }

    public function create(float $amount, int $userId): int
    {
        $this->db->insert('invoices', ['amount' => $amount, 'user_id' => $userId]);

        return (int)$this->db->lastInsertId();
    }
}
