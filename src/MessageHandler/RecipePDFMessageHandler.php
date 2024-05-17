<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Message\RecipePDFMessage;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

/**
 * Class RecipePDFMessageHandler
 *
 * @author Trésor-ILUNGA <hello@tresor-ilunga.tech>
 */
#[AsMessageHandler]
final class RecipePDFMessageHandler
{
    public function __construct(
        #[Autowire('%kernel.project_dir%/public/pdfs')]
        private readonly string $path
    ){

    }
    public function __invoke(RecipePDFMessage $message)
    {
        file_put_contents($this->path . '/' . $message->id . '.pdf','');
    }
}
