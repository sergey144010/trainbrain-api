<?php

namespace App\Connections;

use MongoDB\Client;
use MongoDB\Collection;

class MongoDbClient
{
    private Collection $collection;

    public function __construct()
    {
        $this->collection = (new Client(
            uri: 'mongodb://mongo/'
        ))->trainbrain->definitions;
    }

    public function collection(): Collection
    {
        return $this->collection;
    }
}