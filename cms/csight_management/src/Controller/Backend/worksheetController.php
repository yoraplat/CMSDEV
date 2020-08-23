<?php

namespace App\Controller\Backend;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Worksheet;
use App\Entity\Period;
use Symfony\Component\HttpFoundation\Request;
use App\Form\EditWorksheetFormType;


class worksheetController extends AbstractController
{
    /**
     * @Route("/admin/worksheets/{periodId}", defaults={"periodId"=1}, name="backend_worksheets")
     */
    public function index($periodId)
    {
        $worksheets = $this->getDoctrine()
        ->getRepository(Worksheet::class)
        ->findBy(array('periodId' => $periodId));
        
        $periods = $this->getDoctrine()
        ->getRepository(period::class)
        ->findAll();

        return $this->render('backend/worksheet/index.html.twig', [
            'worksheets' => $worksheets,
            'periods' => $periods,
        ]);
    }
    
    /**
     * @Route("/admin/worksheets/{id}/edit", name="edit_worksheet")
     */
    public function edit(Worksheet $worksheet, $id, Request $request)
    {
        $form = $this->createForm(EditWorksheetFormType::class, $worksheet, array(
            'method' => 'PUT',
        ));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $worksheet = $form->getData();
            $worksheet->setEmployeeId($worksheet->getEmployee()->getId());
            $worksheet->setPeriodId($worksheet->getPeriod()->getId());
            $worksheet->setUpdatedAt(new \DateTime);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($worksheet);
            $entityManager->flush();

            return $this->redirectToRoute('backend_worksheets');
        }

        return $this->render('backend/worksheet/update.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
