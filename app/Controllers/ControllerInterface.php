<?php

namespace App\Controllers;

interface ControllerInterface
{
    public function handle(mixed ...$options): void;
}
