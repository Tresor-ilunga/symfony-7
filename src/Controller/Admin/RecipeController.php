<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

/**
 * Class RecipeController
 *
 * @author Trésor-ILUNGA <hello@tresor-ilunga.tech>
 */
#[Route("/admin/recettes", name: 'admin.recipe.')]
class RecipeController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(RecipeRepository $repository, EntityManagerInterface $em): Response
    {
        $recipes = $repository->findWithDurationLowerThan(20);
        return $this->render(
            view: 'admin/recipe/index.html.twig',
            parameters: [
                'recipes' => $recipes,
            ]
        );
    }

    #[Route('/create', name: 'create', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $recipe = new Recipe();
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $em->persist($recipe);
            $em->flush();
            $this->addFlash('success', 'La recette a bien été créee');
            return $this->redirectToRoute('admin.recipe.index');
        }
        return $this->render(
            view: 'admin/recipe/create.html.twig',
            parameters: [
                'form' => $form
            ]
        );
    }

    #[Route('/{id}', name: 'edit', requirements: ['id' => Requirement::DIGITS], methods: ['GET', 'POST'])]
    public function edit(Recipe $recipe, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $em->flush();
            $this->addFlash('success', 'La recette a bien été modifiée');
            return $this->redirectToRoute('admin.recipe.index');
        }
        return $this->render(
            view: 'admin/recipe/edit.html.twig',
            parameters: [
                'recipe' => $recipe,
                'form' => $form
            ]
        );
    }

    #[Route('/{id}', name: 'delete', requirements: ['id' => Requirement::DIGITS], methods: ['GET', 'POST'])]
    public function remove(Recipe $recipe, EntityManagerInterface $em): Response
    {
        $em->remove($recipe);
        $em->flush();
        $this->addFlash('success', 'La recette a bien été supprimée');
        return $this->redirectToRoute('admin.recipe.index');
    }
}
