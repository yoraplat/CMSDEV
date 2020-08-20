<?php

namespace App\Controller\Frontend;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Worksheet;
use App\Entity\Period;

class WorksheetsController extends AbstractController
{
    /**
     * @Route("/worksheets/{id}", name="frontend_worksheets")
     */
    public function index($id)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('backend_dashboard');
        } elseif ($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYEE')) {
            return $this->redirectToRoute('unauthorized');
        } else {

            $worksheets = $this->getDoctrine()
            ->getRepository(Worksheet::class)
            ->findBy(array('periodId' => $id));
            
            $period = $this->getDoctrine()
            ->getRepository(Period::class)
            ->find($id);

            // Get all non accepted worksheets for this user
            // ->findBy(array('clientId' => $this->getUser(), 'acceptedByClient' => false));

            return $this->render('frontend/worksheets.html.twig', [
                'worksheets' => $worksheets,
                'period' => $period,
            ]);
        }
    }

    /**
     * @Route("/recent", name="frontend_recent")
     */
    public function recent()
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('backend_dashboard');
        } elseif ($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYEE')) {
            return $this->redirectToRoute('unauthorized');
        } else {

            // $worksheets = $this->getDoctrine()
            // ->getRepository(Worksheet::class)
            // ->findBy(array('periodId' => $id));
            
            $periods = $this->getDoctrine()
            ->getRepository(Period::class)
            ->findBy(array('clientId' => $this->getUser()));

            // Get all non accepted worksheets for this user
            // ->findBy(array('clientId' => $this->getUser(), 'acceptedByClient' => false));

            return $this->render('frontend/recent.html.twig', [
                // 'worksheets' => $worksheets,
                'periods' => $periods,
            ]);
        }
    }
    
    
    /**
     * @Route("/worksheets/accept/{id}", name="frontend_accept_worksheet")
     */
    public function accept($id)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('backend_dashboard');
        } elseif ($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYEE')) {
            return $this->redirectToRoute('unauthorized');
        } else {

        $entityManager = $this->getDoctrine()->getManager();
        $worksheet = $entityManager->getRepository(Worksheet::class)->find($id);
        
        $worksheet->setAcceptedByClient(true);
        $entityManager->flush();

        // $worksheet = $this->getDoctrine()
        // ->getRepository(Worksheet::class)
        // ->find($id);



        return $this->redirectToRoute('frontend_worksheets');
        }
    }
}
