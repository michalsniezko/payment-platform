<?php
declare(strict_types=1);

namespace App;

use Generator;
use PDOStatement;

abstract class Model
{
    protected DB $db;

    public function __construct()
    {
        $this->db = App::db();
    }

    public function fetchLazy(PDOStatement $statement): Generator
    {
        foreach ($statement as $item) {
            yield $item;
        }
    }
}
