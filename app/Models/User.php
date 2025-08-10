<?php
declare(strict_types=1);

namespace App\Models;

use App\Model;
use Doctrine\DBAL\Exception;

class User extends Model
{
    /**
     * @throws Exception
     */
    public function create(string $email, string $name, bool $isActive = true): int
    {
        $this->db->insert('users', [
            'email'      => $email,
            'full_name'  => $name,
            'is_active'  => $isActive,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        return (int) $this->db->lastInsertId();
    }
}
