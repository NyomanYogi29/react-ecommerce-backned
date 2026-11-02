<?php

namespace App\Contracts;

interface CategoryInterface
{
    public function getIdByName(string $name): string;
}
