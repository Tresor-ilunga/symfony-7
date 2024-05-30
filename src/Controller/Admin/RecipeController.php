<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Message\RecipePDFMessage;
use App\Repository\RecipeRepository;
use App\Security\Voter\RecipeVoter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\UX\Turbo\TurboBundle;

/**
 * Class RecipeController
 *
 * @author Trésor-ILUNGA <hello@tresor-ilunga.tech>
 */
#[Route("/admin/recettes", name: 'admin.recipe.')]
class RecipeController extends AbstractController
{
    /**
     * @param RecipeRepository $repository
     * @param Request $request
     * @param Security $security
     * @return Response
     */
    #[Route('/', name: 'index')]
    #[IsGranted(RecipeVoter::LIST)]
    public function index(RecipeRepository $repository, Request $request, Security $security): Response
    {
        $page = $request->query->getInt('page', 1);
        $userId = $security->getUser()->getId();
        $canListAll = $security->isGranted(RecipeVoter::LIST_ALL);
        $recipes = $repository->paginateRecipes($page, $canListAll ? null : $userId);
        return $this->render(
            view: 'admin/recipe/index.html.twig',
            parameters: [
                'recipes' => $recipes,
            ]
        );
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return Response
     */
    #[Route('/create', name: 'create', methods: ['GET', 'POST'])]
    #[IsGranted(RecipeVoter::CREATE)]
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

    /**
     * @param Recipe $recipe
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param MessageBusInterface $messageBus
     * @return Response
     */
    #[Route('/{id}', name: 'edit', requirements: ['id' => Requirement::DIGITS], methods: ['GET', 'POST'])]
    #[IsGranted(RecipeVoter::EDIT, subject: 'recipe')]
    public function edit(Recipe $recipe, Request $request, EntityManagerInterface $em, MessageBusInterface $messageBus): Response
    {
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $em->flush();
            $messageBus->dispatch(new RecipePDFMessage($recipe->getId()));
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

    /**
     * @param Request $request
     * @param Recipe $recipe
     * @param EntityManagerInterface $em
     * @return Response
     */
    #[Route('/{id}', name: 'delete', requirements: ['id' => Requirement::DIGITS], methods: ['GET', 'POST'])]
    #[IsGranted(RecipeVoter::EDIT, subject: 'recipe')]
    public function remove(Request $request,Recipe $recipe, EntityManagerInterface $em): Response
    {
        $recipeId = $recipe->getId();
        $em->remove($recipe);
        $em->flush();
        if ($request->getPreferredFormat() === TurboBundle::STREAM_FORMAT)
        {
            $request->setRequestFormat(TurboBundle::STREAM_FORMAT);
            return $this->render(
                view: 'admin/recipe/delete.html.twig',
                parameters: [
                    'recipeId' => $recipeId
                ]
            );
        }
        $this->addFlash('success', 'La recette a bien été supprimée');
        return $this->redirectToRoute('admin.recipe.index');
    }
}
