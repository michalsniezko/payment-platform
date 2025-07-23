<?php
declare(strict_types=1);

namespace App\Models;

use App\Model;

class Invoice extends Model
{
    public function create(float $amount, int $userId): int
    {
        $stmt = $this->db->prepare('insert into invoices (amount, user_id) values (?, ?)');

        $stmt->execute([$amount, $userId]);

        return (int) $this->db->lastInsertId();
    }

    public function find(int $invoiceId): array
    {
        $stmt = $this->db->prepare('
        select i.id, i.amount, u.full_name
        from invoices i
        left join users u on u.id = i.user_id
        where i.id = ?
        ');

        $stmt->execute([$invoiceId]);

        $invoice = $stmt->fetch();
        return $invoice ?: [];
    }
}
