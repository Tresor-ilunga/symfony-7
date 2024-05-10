<?php

declare(strict_types=1);

namespace App\Controller\API;

use App\Entity\Recipe;
use App\Repository\RecipeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

/**
 * Class RecipesController
 *
 * @author Trésor-ILUNGA <hello@tresor-ilunga.tech>
 */
class RecipesController extends AbstractController
{
    #[Route("/api/recipes")]
    public function index(RecipeRepository $repository, Request $request): JsonResponse
    {
        $recipes = $repository->paginateRecipes($request->query->getInt('page', 1));
        return $this->json($recipes, 200, [], [
            'groups' => ['recipe.index']
        ]);
    }

    #[Route("/api/recipes/{id}", requirements: ['id' => Requirement::DIGITS])]
    public function show(Recipe $recipe): JsonResponse
    {
        return $this->json($recipe, 200, [], [
            'groups' => ['recipe.index', 'recipes.show']
        ]);
    }
}