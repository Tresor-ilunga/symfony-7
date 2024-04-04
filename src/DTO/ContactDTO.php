<?php

declare(strict_types=1);

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class ContactDTO
 *
 * @author Trésor-ILUNGA <hello@tresor-ilunga.tech>
 */
class ContactDTO
{
    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 200)]
    public string $name = '';

    #[Assert\NotBlank()]
    #[Assert\Email()]
    public string $email = '';

    #[Assert\NotBlank()]
    #[Assert\Length(min: 3, max: 200)]
    public string $message = '';

    #[Assert\NotBlank()]
    public string $service = '';
}