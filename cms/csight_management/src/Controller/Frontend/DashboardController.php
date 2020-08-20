<?php

namespace App\Controller\Frontend;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Period;

class DashboardController extends AbstractController
{
    /**
     * @Route("/dashboard", name="frontend_dashboard")
     */
    public function index()
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('backend_dashboard');
        } elseif ($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYEE')) {
            return $this->redirectToRoute('unauthorized');
        } else {

            $periods = $this->getDoctrine()
            ->getRepository(Period::class)
            ->findBy(array('clientId' => $this->getUser()));
            
            // dd($periods);
            return $this->render('frontend/dashboard.html.twig', [
                'periods' => $periods,
            ]);
        }
    }
}
