<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\PromoCode;
use App\Entity\User;
use App\Form\PromoCodeFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class PromoCodeController extends AbstractController
{
    #[Route('/promocodes', name: 'app_promocodes')]
    public function getCodePromos(EntityManagerInterface $entityManager): Response
    {
        $promocodes = $entityManager->getRepository(PromoCode::class)->findAll();

        usort($promocodes, function ($a, $b) {
            return count($b->getComments()) - count($a->getComments());
        });

        return $this->render('promo_code/promocodes.html.twig', [
            'controller_name' => 'PromoCodeController',
            'promocodes' => $promocodes,
        ]);
    }

    #[Route('/promocodes/hot', name: 'app_promocodes_hot')]
    public function getHotPromoCodes(EntityManagerInterface $entityManager): Response
    {
        $promocodes = $entityManager->getRepository(PromoCode::class)->findHotPromoCodes();

        usort($promocodes, function ($a, $b) {
            return $b->getPublicationDatetime() <=> $a->getPublicationDatetime();
        });

        return $this->render('deals/hot/dealsHot.html.twig', [
            'controller_name' => 'DealsController',
            'deals' => $promocodes,
        ]);
    }

    #[Route('/promocodes/detail', name: 'app_promocode_detail')]
    public function getPromocodeDetail(Request $request, EntityManagerInterface $entityManager): Response
    {
        $promocodeId = $request->query->get('promocodeId');
        $promocode = $entityManager->getRepository(PromoCode::class)->find($promocodeId);

        $comments = $entityManager->getRepository(Comment::class)->findBy(
            ['promoCode' => $promocode],
            ['datetime' => 'DESC']
        );

        return $this->render('promo_code/detail/detail.html.twig', [
            'controller_name' => 'PromoCodeController',
            'promocode' => $promocode,
            'comments' => $comments,
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
            $promocode->setHotLevel(0);
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

    #[Route('/promocodes/hotLevel/decrease', name: 'app_promocodes_decrease_hotlevel')]
    public function decreaseHotLevel(Request $request, EntityManagerInterface $entityManager): Response
    {
        $promocodeId = $request->query->get('promocodeId');
        $promocode = $entityManager->getRepository(PromoCode::class)->find($promocodeId);

        $promocode->setHotLevel($promocode->getHotLevel() - 1);

        $entityManager->persist($promocode);
        $entityManager->flush();

        return $this->redirectToRoute('app_promocodes');
    }

    #[Route('/promocodes/hotLevel/increase', name: 'app_promocodes_increase_hotlevel')]
    public function increaseHotLevel(Request $request, EntityManagerInterface $entityManager): Response
    {
        $promocodeId = $request->query->get('promocodeId');
        $promocode = $entityManager->getRepository(PromoCode::class)->find($promocodeId);

        $promocode->setHotLevel($promocode->getHotLevel() + 1);

        $entityManager->persist($promocode);
        $entityManager->flush();

        return $this->redirectToRoute('app_promocodes');
    }

    #[Route('/promocodes/report', name: 'app_promocodes_report')]
    public function reportPromocode(Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer): Response
    {
        $promocodeId = $request->query->get('promocodeId');
        $promocode = $entityManager->getRepository(PromoCode::class)->find($promocodeId);

        $email = (new Email())
            ->from('report-noreply@gmail.com')
            ->to('admin@gmail.com')
            ->subject('Promo code report')
            ->text('This promo code was reported'."\n".
                '- promo code id: '.$promocode->getId()."\n".
                '- promo code title: '.$promocode->getTitle()."\n".
                '- promo code description: '.$promocode->getDescription()."\n");

        $mailer->send($email);

        return $this->redirectToRoute('app_promocode_detail', [
            'promocodeId' => $promocode->getId(),
        ]);
    }

    #[Route('/promocodes/save', name: 'app_promocodes_save')]
    public function saveDeal(Request $request, EntityManagerInterface $entityManager): Response
    {
        $promocodeId = $request->query->get('dealId');
        $promocode = $entityManager->getRepository(PromoCode::class)->find($promocodeId);
        $user = $this->getUser();

        if ($user instanceof User) {
            $user->addPromoCodesSave($promocode);
            $entityManager->persist($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_deal_detail', [
            'promocodeId' => $promocode->getId(),
        ]);
    }
}
