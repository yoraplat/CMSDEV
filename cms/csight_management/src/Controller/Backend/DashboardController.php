<?php

namespace App\Controller\Backend;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use FOS\UserBundle\Mailer\MailerInterface;

use App\Entity\Period;
use App\Form\EditPeriodFormType;
use App\Form\NewPeriodFormType;


class DashboardController extends AbstractController
{
    /**
     * @Route("/admin/dashboard", name="backend_dashboard")
     */
    public function index()
    {
        // Get all periods
        $periods = $this->getDoctrine()
        ->getRepository(Period::class)
        ->findAll();

        return $this->render('backend/dashboard/index.html.twig', [
            "periods" => $periods,
        ]);
    }
    
    /**
     * @Route("/admin/period/create", name="add_period")
     */
    public function addPeriod(Request $request)
    {
        $period = new Period();

        $form = $this->createForm(NewPeriodFormType::class, $period);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $period = $form->getData();
            $period->setClientId($period->getClient()->getId());
            $period->setAcceptedByClient(false);
            $period->setCreatedAt(new \DateTime);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($period);
            $entityManager->flush();

            // email
            $em = $this->getDoctrine()->getManager();
            $transport = (new \Swift_SmtpTransport('smtp.mailtrap.io', 2525))
            ->setUsername('7fbc51688f464f')
            ->setPassword('f799a7f36d6e9a');

            $mailer = new \Swift_Mailer($transport);

            $message = (new \Swift_Message('Nieuwe periode aangemaakt | C-SIGHT'))
            ->setSubject('Nieuwe periode aangemaakt')
            ->setFrom(['csight@mail.com'])
            ->setTo([$period->getClient()->getEmail()])
            ->setBody(
                $this->renderView(
                    'email/period/new_period.html.twig',
                    [
                        'period' => $period
                    ]
                ), 'text/html'
            );
            $result = $mailer->send($message);
            $this->addFlash('success', 'Mail verzonden');

            return $this->redirectToRoute('backend_dashboard');
        }
        

        return $this->render('backend/period/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/period/{id}/edit", name="edit_period")
     */
    public function editPeriod(Period $period, $id, Request $request)
    {

        $form = $this->createForm(EditPeriodFormType::class, $period, array(
            'method' => 'PUT',
        ));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $period = $form->getData();
            $period->setClientId($period->getClient()->getId());
            $period->setUpdatedAt(new \DateTime);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($period);
            $entityManager->flush();

            return $this->redirectToRoute('backend_dashboard');
        }
        

        return $this->render('backend/period/update.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
