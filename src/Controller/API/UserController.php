<?php

declare(strict_types=1);

namespace App\Controller\API;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * Class UserController
 *
 * @author Trésor-ILUNGA <hello@tresor-ilunga.tech>
 */
class UserController extends AbstractController
{
    #[Route('api/me')]
    #[IsGranted("ROLE_USER")]
    public function me(): JsonResponse
    {
        return $this->json($this->getUser());
    }
}