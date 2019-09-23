<?php

namespace App\Controller;

use App\Entity\Documentation;
use App\Form\DocumentationType;
use App\Repository\CategoryRepository;
use App\Repository\DocumentationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/")
 */
class PublicController extends AbstractController
{

    /**
     * @Route("/{path}", name="home", methods={"GET"})
     */
    public function index($path = '', CategoryRepository $cr, DocumentationRepository $dr): Response
    {
        $documentation = $path ? $dr->findOneBy(['path' => $path]) : $dr->findOneBy(['path' => null]);
        if(!$documentation){
            $category = $cr->findOneBy(['path' => $path]);
            $docs = $dr->findBy(['category'=>$category->getId()], ['title'=> 'ASC']);
            return $this->render('category.html.twig', [
              'docs' => $docs,
              'category' => $category,
            ]);
        }
        return $this->render('documentation.html.twig', [
          'documentation' => $documentation,
        ]);
    }

}