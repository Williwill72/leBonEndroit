<?php

namespace App\Controller;

use App\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home()
    {
        $articleRepository =$this->getDoctrine()->getRepository(Article::class);

        $articles = $articleRepository->findAll();

        return $this->render("default/home.html.twig",[
            "articles" => $articles
        ]);
    }
}