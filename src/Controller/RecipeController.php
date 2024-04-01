<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use function Symfony\Component\Translation\t;

/**
 * Class RecipeController
 *
 * @author Trésor-ILUNGA <hello@tresor-ilunga.tech>
 */
class RecipeController extends AbstractController
{
    #[Route('/recettes', name: 'recipe.index')]
    public function index(Request $request, RecipeRepository $repository, EntityManagerInterface $em): Response
    {
        $recipes = $repository->findWithDurationLowerThan(20);
        return $this->render(
            view: 'recipe/index.html.twig',
            parameters: [
                'recipes' => $recipes,
            ]
        );
    }


    #[Route('/recettes/{slug}-{id}', name: 'recipe.show', requirements: ['id' => '\d+', 'slug' => '[a-z0-9-]+'])]
    public function show(Request $request, string $slug, int $id, RecipeRepository $repository): Response
    {
        $recipe = $repository->find($id);
        if ($recipe->getSlug() !== $slug) {
            return $this->redirectToRoute('recipe.show', ['slug' => $recipe->getSlug(), 'id' => $recipe->getId()]);
        }
        return $this->render(
            view: 'recipe/show.html.twig',
            parameters: [
                'recipe' => $recipe,
            ]
        );
    }

    #[Route('/recettes/{id}/edit', name: 'recipe.edit', methods: ['GET', 'POST'])]
    public function edit(Recipe $recipe, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $em->flush();
            $this->addFlash('sucess', 'La recette a bien été modifiée');
            return $this->redirectToRoute('recipe.index');
        }
        return $this->render(
            view: 'recipe/edit.html.twig',
            parameters: [
                'recipe' => $recipe,
                'form' => $form
            ]
        );
    }
}
