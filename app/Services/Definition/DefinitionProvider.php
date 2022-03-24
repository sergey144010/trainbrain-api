<?php

namespace App\Services\Definition;

use App\Entities\Definition;

class DefinitionProvider
{
    public function __construct(private DefinitionRepositoryInterface $repository)
    {
    }

    /** @return Array<Definition> */
    public function getCollection(): array
    {
        return array_map(function ($item) {
            if (! isset($item['id'])) {
                throw new DefinitionProviderException();
            }
            if (! isset($item['name'])) {
                throw new DefinitionProviderException();
            }
            if (! isset($item['definition'])) {
                throw new DefinitionProviderException();
            }

            return new Definition(
                (int) $item['id'],
                (string) $item['name'],
                (string) $item['definition']
            );
        }, $this->repository->definitionCollection());
    }
}
