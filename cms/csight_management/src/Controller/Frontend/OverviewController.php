<?php

namespace App\Controller\Frontend;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Response;

use App\Entity\Period;

use Dompdf\Dompdf;
use Dompdf\Options;

class OverviewController extends AbstractController
{
    /**
     * @Route("/overview/{id}", name="frontend_overview")
     */
    public function index($id)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('backend_dashboard');
        } elseif ($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYEE')) {
            return $this->redirectToRoute('unauthorized');
        } else {
        
            $period = $this->getDoctrine()
            ->getRepository(Period::class)
            ->findOneBy(array('clientId' => $this->getUser(), 'id' => $id));

            $pdfOptions = new Options();
            $pdfOptions->setIsPhpEnabled(true);
            $pdfOptions->set('defaultFont', 'Arial');
            
            $dompdf = new Dompdf($pdfOptions);
            
            $html = $this->renderView('frontend/period_overview.html.twig', [
                'period' => $period
            ]);

            $dompdf->loadHtml($html);
            $dompdf->render();
            $dompdf->stream('factuur', array('Attachment'=>false));
            
            exit;
        }
    }
    
}
