<?php

namespace App\Repositories;

use App\Connections\MongoDbClient;
use App\Services\Definition\DefinitionRepositoryInterface;
use MongoDB\Collection;
use MongoDB\Model\BSONDocument;

class DefinitionRepository implements DefinitionRepositoryInterface
{
    private Collection $collection;

    public function __construct()
    {
        $this->collection = (new MongoDbClient())->collection();
    }

    /** @return Array<string, mixed> */
    public function definitionCollection(): array
    {
        return array_map(function (BSONDocument $document) {
                return $document->getArrayCopy();
        }, $this->collection->find()->toArray());
    }
}
