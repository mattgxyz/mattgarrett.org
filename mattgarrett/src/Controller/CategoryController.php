<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/category")
 */
class CategoryController extends AbstractController
{

    /**
     * @Route("/", name="admin-category", methods={"GET"})
     */
    public function index(CategoryRepository $categoryRepository): Response
    {
        return $this->render('category/index.html.twig', [
          'categories' => $categoryRepository->findAll(),
        ]);
    }


    /**
     * @Route("/edit/{id}", name="admin-category-edit", methods={"GET","POST"})
     */
    public function edit(Request $request, $id = '', CategoryRepository $cr): Response
    {
        if ($id) {
            $category = $cr->find($id);
        } else {
            $category = new Category();
        }
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->persist($category);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin-category');
        }

        return $this->render('category/edit.html.twig', [
          'category' => $category,
          'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{category}", name="admin-category-delete", methods={"GET"})
     */
    public function delete(Request $request, Category $category): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($category);
        $entityManager->flush();
        return $this->redirectToRoute('admin-category');
    }

}
