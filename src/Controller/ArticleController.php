<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Category;
use App\Form\AddArticleType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    /**
     * @Route(
     *     "/article/ajouter"
     *      , name="article_add"
     *      , methods={"GET", "POST"}
     * )
     */
    public function addArticle(Request $request){

        $article = new Article();
        $articleForm = $this->createForm(AddArticleType::class, $article);
        $articleForm->handleRequest($request);

        if($articleForm->isSubmitted() && $articleForm->isValid())
        {
            $article->setUser($this->getUser());

            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();

            $this->addFlash('success', 'Votre article a bien été ajouté');
            return $this->redirectToRoute('article_list');
        }

        return $this->render("article/create.html.twig",[
            "articleForm" => $articleForm->createView()
        ]);

    }

    /**
     * @Route(
     *          "/article/list",
     *          name="article_list",
     *          methods={"POST"}
     *     )
     */
    public function TriArticle()
    {
        $articleRepository = $this->getDoctrine()->getRepository(Article::class);
        $categoryRepository = $this->getDoctrine()->getRepository(Category::class);



        $categories = $categoryRepository->findAll();
        $articles = $articleRepository->findAll();

        return $this->render("article/list.html.twig",[
            "articles" => $articles,
            "categories" => $categories
        ]);
    }

    /**
     * @Route(
     *          "/article/list",
     *          name="article_list",
     *          methods={"GET","POST"}
     *     )
     */
    public function listArticle()
    {
        $articleRepository = $this->getDoctrine()->getRepository(Article::class);
        $categoryRepository = $this->getDoctrine()->getRepository(Category::class);

        $cat = $this->get("category");
        var_dump($cat);

        $categories = $categoryRepository->findAll();
        $articles = $articleRepository->findAll();

        return $this->render("article/list.html.twig",[
            "articles" => $articles,
            "categories" => $categories
        ]);
    }

    /**
     * @Route("/article/detail/{id}",
     *          name="article_detail",
     *          requirements={"id": "\d+"},
     *          methods={"GET"}
     *     )
     */
    public function detailArticle($id)
    {
        $articleRepository = $this->getDoctrine()->getRepository(Article::class);
        $article = $articleRepository->find($id);
        return $this->render("article/detail.html.twig",[
            "article" => $article
        ]);
    }

    /**
     * @Route(
     *     "article/remove/{id}",
     *     name="article_remove",
     *     requirements={"id": "\d+"},
     *     methods={"GET"}
     *     )
     */
    public function removeArticle($id)
    {
        $articleRepository = $this->getDoctrine()->getRepository(Article::class);
        $articleRepository->remove($id);
        return $this->redirectToRoute("account");
    }

}