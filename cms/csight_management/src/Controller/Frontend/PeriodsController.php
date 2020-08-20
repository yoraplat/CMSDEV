<?php

namespace App\Controller\Frontend;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Period;

class PeriodsController extends AbstractController
{
    /**
     * @Route("/periods", name="frontend_periods")
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
            // Get all non accepted periods for this user
            // ->findBy(array('clientId' => $this->getUser(), 'acceptedByClient' => false));

            return $this->render('frontend/periods.html.twig', [
                'periods' => $periods,
            ]);
        }
    }
    
    
    /**
     * @Route("/periods/accept/{id}", name="frontend_accept_period")
     */
    public function accept($id)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('backend_dashboard');
        } elseif ($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYEE')) {
            return $this->redirectToRoute('unauthorized');
        } else {

        $entityManager = $this->getDoctrine()->getManager();
        $period = $entityManager->getRepository(Period::class)->find($id);
        
        $period->setAcceptedByClient(true);
        $entityManager->flush();

        // $period = $this->getDoctrine()
        // ->getRepository(Period::class)
        // ->find($id);



        return $this->redirectToRoute('frontend_periods');
        }
    }
}
