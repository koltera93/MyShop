<?php

namespace App\Controller;

use App\Entity\FeedBackRequest;
use App\Form\FeedbackType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class FeedbackController extends AbstractController
{
    /**
     * @Route("/feedback", name="feedback")
     */
    public function index(Request $request, EntityManagerInterface $entityManager)
    {

        $feedBackRequest = new FeedBackRequest();

        $form = $this->createForm(FeedbackType::class, $feedBackRequest);

        $form = $this->createFormBuilder($feedBackRequest)
            ->add('name')
            ->add('email')
            ->add('message')
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $entityManager->persist($feedBackRequest);
            $entityManager->flush();

            $this->addFlash('success' , 'Спасибо за обращение, мы обязательно с вами свяжемся!');

            return $this->redirectToRoute('feedback');
        }

        return $this->render('feedback/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
