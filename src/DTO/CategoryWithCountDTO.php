<?php

declare(strict_types=1);

namespace App\DTO;

/**
 * Class CategoryWithCountDTO
 *
 * @author Trésor-ILUNGA <hello@tresor-ilunga.tech>
 */
class CategoryWithCountDTO
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly int $count
    ){}
}