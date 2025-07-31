<?php
declare(strict_types=1);

namespace App\DTO;

readonly class Address
{
    public function __construct(
        public string $country,
        public string $state,
        public string $city,
        public string $street,

        public string $houseNumber,
        public string $zip,
    )
    {
    }
}
