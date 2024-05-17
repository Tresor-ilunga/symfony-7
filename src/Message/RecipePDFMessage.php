<?php

declare(strict_types=1);

namespace App\Message;

/**
 * Class RecipePDFMessage
 *
 * @author Trésor-ILUNGA <hello@tresor-ilunga.tech>
 */
final class RecipePDFMessage
{
   public function __construct(
     public readonly int $id
   ){}
}
