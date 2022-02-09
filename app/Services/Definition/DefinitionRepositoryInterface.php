<?php

namespace App\Services\Definition;

interface DefinitionRepositoryInterface
{
    public function definitionCollection(): array;
}