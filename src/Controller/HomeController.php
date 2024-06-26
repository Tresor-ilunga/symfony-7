<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Class HomeController
 *
 * @author Trésor-ILUNGA <hello@tresor-ilunga.tech>
 */
class HomeController extends AbstractController
{
    /**
     * @return Response
     */
    #[Route('/', name: 'home')]
    public function index(): Response
    {
        return $this->render(
            view: 'home/index.html.twig'
        );
    }
}
