<?php

namespace App\DTOs;

class Coordinates
{
    public function __construct(
        public readonly string $latitude,
        public readonly string $longitude
    ) {}
}
