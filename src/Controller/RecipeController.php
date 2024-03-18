<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Class RecipeController
 *
 * @author Trésor-ILUNGA <hello@tresor-ilunga.tech>
 */
class RecipeController extends AbstractController
{
    #[Route('/recette', name: 'recipe.index')]
    public function index(Request $request): Response
    {
        return $this->render(
            view: 'recipe/index.html.twig',
        );
    }


    #[Route('/recette/{slug}-{id}', name: 'recipe.show', requirements: ['id' => '\d+', 'slug' => '[a-z0-9-]+'])]
    public function show(Request $request, string $slug, int $id): Response
    {
        return $this->render(
            view: 'recipe/show.html.twig',
            parameters: [
                'slug' => $slug,
                'id' => $id,
            ]
        );
    }
}
