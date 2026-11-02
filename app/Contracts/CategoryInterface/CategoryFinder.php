<?php

namespace App\Contracts\CategoryInterface;

interface CategoryFinderInterface {
    public function getIdByName(string $name): string;
}