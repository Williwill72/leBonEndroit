<?php

namespace App\Controller;


use App\Entity\Article;
use App\Entity\User;
use App\Repository\ArticleRepository;
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
        return $this->render("user/monCompte.html.twig");
    }

    /**
     * @Route(
     *     "profil/mes-articles",
     *     name="article",
     *     methods={"GET","POST"}
     *     )
     */
    public function mesArticles()
    {
        $articleRepository = $this->getDoctrine()->getRepository(Article::class);

        $userArticle = $articleRepository->findByUserId($this->getUser());

        return $this->render("user/mesArticles.html.twig",[
            "articles" => $userArticle
        ]);
    }

    /**
     * @Route(
     *     "profil/mes-favoris",
     *     name="favoris",
     *     methods={"GET","POST"}
     *     )
     */
    public function mesFavoris()
    {
        $articleRepository = $this->getDoctrine()->getRepository(Article::class);

        $userFavorite = $articleRepository->findByFavorite();

        return $this->render("user/favoris.html.twig",[
            "favorites" => $userFavorite
        ]);
    }

}