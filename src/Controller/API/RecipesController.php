<?php

declare(strict_types=1);

namespace App\Controller\API;

use App\DTO\PaginationDTO;
use App\Entity\Recipe;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

/**
 * Class RecipesController
 *
 * @author Trésor-ILUNGA <hello@tresor-ilunga.tech>
 */
class RecipesController extends AbstractController
{
    #[Route("/api/recipes", methods: ["GET"])]
    public function index(
        RecipeRepository $repository,
        #[MapQueryString]
        ?PaginationDTO $paginationDTO = null
    ): JsonResponse
    {
        $recipes = $repository->paginateRecipes($paginationDTO?->page);
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

    #[Route("/api/recipes", methods: ["POST"])]
    public function create(Request $request,
    #[MapRequestPayload(
        serializationContext: [
            'groups' => ['recipe.create']
        ]
    )]
    Recipe $recipe,
    EntityManagerInterface $em
    ): JsonResponse
    {
        $em->persist($recipe);
        $em->flush();
        return $this->json($recipe, 200, [], [
            'groups' => ['recipe.index', 'recipes.show']
        ]);
    }

}