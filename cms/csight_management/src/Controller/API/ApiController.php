<?php

namespace App\Controller\API;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


class ApiController extends AbstractController
{
    /**
     * @Route("/authentication_token", name="authentication_token", methods={"POST"})
     */
    public function index()
    {
        return $this->json(['name' => 'test']);
    }
}
