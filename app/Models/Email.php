<?php
declare(strict_types=1);

namespace App\Models;

use App\Enum\EmailStatus;
use App\Model;
use Symfony\Component\Mime\Address;

class Email extends Model
{
    public function queue(
        Address $to,
        Address $from,
        string  $subject,
        string  $html,
        ?string $text = null
    ): void
    {
        $meta['to'] = $to->toString();
        $meta['from'] = $from->toString();

        $qb = $this->db->createQueryBuilder();
        $qb->insert('emails')
            ->values([
                'subject' => ':subject',
                'status' => ':status',
                'html_body' => ':html_body',
                'text_body' => ':text_body',
                'meta' => ':meta',
                'created_at' => 'NOW()',
            ])
            ->setParameter('subject', $subject)
            ->setParameter('status', EmailStatus::QUEUE->value)
            ->setParameter('html_body', $html)
            ->setParameter('text_body', $text)
            ->setParameter('meta', json_encode($meta));

        $qb->executeStatement();
    }

    public function getEmailsByStatus(EmailStatus $status): array
    {
        $queryBuilder = $this->db->createQueryBuilder();

        $rows = $queryBuilder
            ->select('*')
            ->from('emails')
            ->where('status = :status')
            ->setParameter('status', $status->value)
            ->fetchAllAssociative();

        return array_map(fn($row) => (object)$row, $rows);
    }

    public function markEmailSent(int $id): void
    {
        $this->db->update(
            'emails',
            [
                'status' => EmailStatus::SENT->value,
                'sent_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => $id,
            ]
        );
    }
}
