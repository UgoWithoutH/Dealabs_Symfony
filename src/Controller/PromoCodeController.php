<?php

namespace App\Controller;

use App\Entity\PromoCode;
use App\Entity\User;
use App\Form\PromoCodeFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PromoCodeController extends AbstractController
{
    #[Route('/promocodes', name: 'app_promocodes')]
    public function getCodePromos(EntityManagerInterface $entityManager): Response
    {
        $promocodes = $entityManager->getRepository(PromoCode::class)->findAll();

        return $this->render('promo_code/promocodes.html.twig', [
            'controller_name' => 'PromoCodeController',
            'promocodes' => $promocodes,
        ]);
    }

    #[Route('/promocodes/add', name: 'app_add_promocode')]
    public function addCodePromo(Request $request, EntityManagerInterface $entityManager): Response
    {
        $promocode = new PromoCode();
        $form = $this->createForm(PromoCodeFormType::class, $promocode);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $promocode->setPublicationDatetime(new \DateTime());
            $user = $this->getUser();

            if ($user instanceof User) {
                $promocode->setAuthor($user);
            }
            $entityManager->persist($promocode);
            $entityManager->flush();

            return $this->redirectToRoute('app_promocodes');
        }

        return $this->render('promo_code/add/addPromocode.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
