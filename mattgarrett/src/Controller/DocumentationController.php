<?php

namespace App\Controller;

use App\Entity\Documentation;
use App\Form\DocumentationType;
use App\Repository\DocumentationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/documentation")
 */
class DocumentationController extends AbstractController
{

    /**
     * @Route("/", name="admin-documentation", methods={"GET"})
     */
    public function index(DocumentationRepository $documentationRepository): Response
    {
        return $this->render('documentation/index.html.twig', [
          'documentations' => $documentationRepository->findAll(),
        ]);
    }


    /**
     * @Route("/edit/{id}", name="admin-documentation-edit", methods={"GET","POST"})
     */
    public function edit(Request $request, $id = '', DocumentationRepository $dr): Response
    {
        if (!$id) {
            $documentation = new Documentation();
        } else {
            $documentation = $dr->find($id);
        }
        $form = $this->createForm(DocumentationType::class, $documentation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->persist($documentation);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin-documentation-edit', ['id' => $id]);
        }

        return $this->render('documentation/edit.html.twig', [
          'documentation' => $documentation,
          'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{documentation}", name="admin-documentation-delete", methods={"GET"})
     */
    public function delete(Request $request, Documentation $documentation): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($documentation);
        $entityManager->flush();
        return $this->redirectToRoute('admin-documentation');
    }

}
