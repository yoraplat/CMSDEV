<?php

namespace App\Controller\Unauthorized;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class UnauthorizedController extends AbstractController
{
    /**
     * @Route("/unauthorized", name="unauthorized")
     */
    public function index()
    {
        return $this->render('error/unauthorized.html.twig');
    }
}
