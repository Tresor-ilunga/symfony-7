<?php

declare(strict_types=1);

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class PaginationDTO
 *
 * @author Trésor-ILUNGA <hello@tresor-ilunga.tech>
 */
class PaginationDTO
{
    public function __construct(
        #[Assert\Positive()]
        public readonly ?int $page = 1
    ){}
}