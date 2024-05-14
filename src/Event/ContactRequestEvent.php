<?php

declare(strict_types=1);

namespace App\Event;

use App\DTO\ContactDTO;

/**
 * Class ContactRequestEvent
 *
 * @author Trésor-ILUNGA <hello@tresor-ilunga.tech>
 */
class ContactRequestEvent
{
    public function __construct(
        public ContactDTO $contact
    ){}
}