<?php
declare(strict_types=1);

namespace App\Enum;

enum Color: string
{
    case GREEN = 'green';
    case RED = 'red';
    case GREY = 'grey';
    case ORANGE = 'orange';

    public function getClass(): string
    {
        return 'color-' . $this->value;
    }
}
