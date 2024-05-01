<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

/**
 * Class CategoryController
 *
 * @author Trésor-ILUNGA <hello@tresor-ilunga.tech>
 */
#[Route('/admin/category', name: 'admin.category.')]
class CategoryController extends AbstractController
{
    #[Route(name: 'index')]
    public function index(CategoryRepository $repository): Response
    {
        return $this->render(
            view: 'admin/category/index.html.twig',
            parameters: [
                'categories' => $repository->findAll()
            ]
        );
    }

    #[Route('/create', name: 'create')]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em->persist($category);
            $em->flush();
            $this->addFlash('success', message: 'La catégorie a bien été créée');
            return $this->redirectToRoute('admin.category.index');
        }
        return $this->render(
            view: 'admin/category/create.html.twig',
            parameters: [
                'form' => $form
            ]
        );
    }

    #[Route('/{id}', name: 'edit', requirements: ['id' => Requirement::DIGITS], methods: ['GET', 'POST'])]
    public function edit(Category $category, Request $request, EntityManagerInterface $em): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em->flush();
            $this->addFlash('success', message: 'La catégorie a bien été modifiée');
            return $this->redirectToRoute('admin.category.index');
        }
        return $this->render(
            view: 'admin/category/edit.html.twig',
            parameters: [
                'category' => $category,
                'form' => $form
            ]
        );
    }

    #[Route('/{id}', name: 'delete', requirements: ['id' => Requirement::DIGITS], methods: ['DELETE'])]
    public function remove(Category $category, EntityManagerInterface $em): Response
    {
        $em->remove($category);
        $em->flush();
        $this->addFlash('success', message: 'La catégorie a bien été supprimée');
        return $this->redirectToRoute('admin.category.index');
    }

}