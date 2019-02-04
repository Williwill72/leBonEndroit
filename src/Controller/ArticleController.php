<?php

namespace App\Controller;

use App\Entity\Article;
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
            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();

            $this->addFlash('success', 'Votre article a bien été ajouté');
            return $this->redirectToRoute('home');
        }

        return $this->render("article/create.html.twig",[
            "articleForm" => $articleForm->createView()
        ]);

    }

}