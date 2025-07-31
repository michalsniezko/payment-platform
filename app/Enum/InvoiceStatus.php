<?php
declare(strict_types=1);

namespace App\Enum;

enum InvoiceStatus: int
{
    case PENDING = 1;
    case PAID = 2;
    case VOID = 3;
    case FAILED = 4;

    public function toString(): string
    {
        return match ($this) {
            self::PAID => 'Paid',
            self::FAILED => 'Failed',
            self::VOID => 'Void',

            default => 'Pending'
        };
    }

    public function color(): Color
    {
        return match ($this) {
            self::PAID => Color::GREEN,
            self::FAILED => Color::RED,
            self::VOID => Color::GREY,
            default => Color::ORANGE
        };
    }
}
