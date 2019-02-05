<?php
/**
 * Created by PhpStorm.
 * User: wdufour2018
 * Date: 04/02/2019
 * Time: 16:42
 */

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("profil/mon-compte", name="account")
     */
    public function monCompte()
    {
        return $this->render("user/monCompte.html.twig");
    }

}