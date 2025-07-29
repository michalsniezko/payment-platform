<?php
declare(strict_types=1);

namespace App\Enum;

enum PaymentStatus: string
{
    case PAID = 'Paid';
    case VOID = 'Void';
    case DECLINED = 'Declined';
}
