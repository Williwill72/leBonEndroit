<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Article;
use App\Entity\Category;
use App\Form\AddArticleType;
use App\Form\CategoryType;
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
     *          methods={"GET","POST"}
     *     )
     */
    public function listArticle(Request $request)
    {
        $articleRepository = $this->getDoctrine()->getRepository(Article::class);
        $categoryRepository = $this->getDoctrine()->getRepository(Category::class);

        $category = new Category();
        $categoryForm = $this->createForm( CategoryType::class,$category);
        $categoryForm->handleRequest($request);

        if($categoryForm->isSubmitted() && $categoryForm->isValid() && $category->getName() !== "Tous")
        {
            $category = $categoryRepository->findBy(array("name" => $category->getName()));
            $articles = $articleRepository->findBy(array("article_category" => $category));
        }
        else
        {
            $articles = $articleRepository->findAll();
        }

        $categories = $categoryRepository->findAll();
        $userFavorites = $articleRepository->findByFavorite();


        return $this->render("article/list.html.twig",[
            "articles" => $articles,
            "categories" => $categories,
            "favorites" => $userFavorites,
            "categoryForm" => $categoryForm->createView()
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

    /**
     * @Route(
     *     "article/list/favorite/{id}",
     *      name="favorite",
     *     requirements={"id":"\d+"},
     *     methods={"GET", "POST"}
     *     )
     */
    public function addFavorite($id)
    {
        $em = $this->getDoctrine()->getManager();
        $articleRepository = $this->getDoctrine()->getRepository( Article::class);
        $userRepository = $this->getDoctrine()->getRepository( User::class);

        $article = $articleRepository->find($id);
        $user = $userRepository->find($this->getUser());

        $user->addFavorite($article);

        $em->persist($user);
        $em->flush();

        return $this->redirectToRoute("article_list");
    }

    /**
     * @Route(
     *     "article/modif/{id}",
     *     name="article_modif",
     *     requirements={"id":"\d+"},
     *     methods={"GET","POST"}
     *     )
     */
    public function displayModifArticle($id)
    {
        $articleRepository = $this->getDoctrine()->getRepository(Article::class);
        $article = $articleRepository->find($id);

        $request = $this->get('request_stack');

        return $this->render("article/modif.html.twig",[
            "article" => $article
        ]);
    }


}