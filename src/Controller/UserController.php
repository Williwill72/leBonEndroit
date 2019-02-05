<?php

namespace App\Controller;


use App\Entity\Article;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route(
     *     "profil/mon-compte",
     *      name="account",
     *     methods={"GET", "POST"})
     */
    public function monCompte()
    {

        $articleRepository = $this->getDoctrine()->getRepository(Article::class);
        $userArticle = $articleRepository->findByUserId($this->getUser());

        return $this->render("user/monCompte.html.twig",[
            "articles" => $userArticle
        ]);
    }



}