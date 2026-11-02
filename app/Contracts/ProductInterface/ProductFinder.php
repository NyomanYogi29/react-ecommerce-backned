<?php

namespace App\Contracts\ProductInterface;

use Illuminate\Database\Eloquent\Collection;

interface ProductFinder {
    public function getAll(): Collection;
}