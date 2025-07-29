<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\Ticket;

readonly class GeneratorExampleController
{
    public function __construct(private Ticket $ticket)
    {
    }

    public function index(): void
    {
        foreach ($this->ticket->all() as $ticket) {
            echo $ticket['id'] . ': ' . substr($ticket['description'], 0, 15) . '<br / >';
        }


    }
}
