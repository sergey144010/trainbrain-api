<?php

namespace App\Services\Definition;

interface DefinitionRepositoryInterface
{
    /** @return Array<string, mixed> */
    public function definitionCollection(): array;
}
