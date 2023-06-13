<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Deal;
use App\Entity\User;
use App\Form\DealFormType;
use App\Utility\AlertsChecker;
use App\Utility\BadgesChecker;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class DealsController extends AbstractController
{
    #[Route('/deals', name: 'app_deals')]
    public function getDeals(EntityManagerInterface $entityManager): Response
    {
        $deals = $entityManager->getRepository(Deal::class)->findAll();

        usort($deals, function ($a, $b) {
            return count($b->getComments()) - count($a->getComments());
        });

        return $this->render('deals/deals.html.twig', [
            'controller_name' => 'DealsController',
            'deals' => $deals,
        ]);
    }

    #[Route('/deals/hot', name: 'app_deals_hot')]
    public function getHotDeals(EntityManagerInterface $entityManager): Response
    {
        $deals = $entityManager->getRepository(Deal::class)->findHotDeals();

        usort($deals, function ($a, $b) {
            return $b->getPublicationDatetime() <=> $a->getPublicationDatetime();
        });

        return $this->render('deals/hot/dealsHot.html.twig', [
            'controller_name' => 'DealsController',
            'deals' => $deals,
        ]);
    }

    #[Route('/deals/detail', name: 'app_deal_detail')]
    public function getDealDetail(Request $request, EntityManagerInterface $entityManager): Response
    {
        $dealId = $request->query->get('dealId');
        $deal = $entityManager->getRepository(Deal::class)->find($dealId);
        $comments = $entityManager->getRepository(Comment::class)->findBy(
            ['deal' => $deal],
            ['datetime' => 'DESC']
        );

        return $this->render('deals/detail/detail.html.twig', [
            'controller_name' => 'DealsController',
            'deal' => $deal,
            'comments' => $comments,
        ]);
    }

    #[Route('/deals/add', name: 'app_add_deal')]
    public function addDeal(Request $request, EntityManagerInterface $entityManager): Response
    {
        $deal = new Deal();
        $form = $this->createForm(DealFormType::class, $deal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $deal->setPublicationDatetime(new \DateTime());
            $deal->setHotLevel(0);
            $user = $this->getUser();

            if ($user instanceof User) {
                $deal->setAuthor($user);
            }
            $entityManager->persist($deal);
            $entityManager->flush();

            BadgesChecker::checkCobaye($user, $entityManager);
            AlertsChecker::checkAlerts($deal, $entityManager);
            return $this->redirectToRoute('app_deals');
        }

        return $this->render('deals/add/addDeal.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/deals/hotLevel/decrease', name: 'app_deals_decrease_hotlevel')]
    public function decreaseHotLevel(Request $request, EntityManagerInterface $entityManager): Response
    {
        $dealId = $request->query->get('dealId');
        $deal = $entityManager->getRepository(Deal::class)->find($dealId);

        $deal->setHotLevel($deal->getHotLevel() - 1);

        $entityManager->persist($deal);
        $entityManager->flush();

        $user = $this->getUser();
        if ($user instanceof User) {
            $user->setNumberOfVotes($user->getNumberOfVotes() + 1);
            BadgesChecker::checkSurveillant($user, $entityManager);
            AlertsChecker::checkAlerts($deal, $entityManager);
        }

        return $this->redirectToRoute('app_deals');
    }

    #[Route('/deals/hotLevel/increase', name: 'app_deals_increase_hotlevel')]
    public function increaseHotLevel(Request $request, EntityManagerInterface $entityManager): Response
    {
        $dealId = $request->query->get('dealId');
        $deal = $entityManager->getRepository(Deal::class)->find($dealId);

        $deal->setHotLevel($deal->getHotLevel() + 1);

        $entityManager->persist($deal);
        $entityManager->flush();

        $user = $this->getUser();
        if ($user instanceof User) {
            $user->setNumberOfVotes($user->getNumberOfVotes() + 1);
            BadgesChecker::checkSurveillant($user, $entityManager);
            AlertsChecker::checkAlerts($deal, $entityManager);
        }

        return $this->redirectToRoute('app_deals');
    }

    #[Route('/deals/report', name: 'app_deals_report')]
    public function reportDeal(Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer): Response
    {
        $dealId = $request->query->get('dealId');
        $deal = $entityManager->getRepository(Deal::class)->find($dealId);

        $email = (new Email())
            ->from('report-noreply@gmail.com')
            ->to('admin@gmail.com')
            ->subject('Deal report')
            ->text('This deal was reported'."\n".
                '- Deal id: '.$deal->getId()."\n".
                '- Deal title: '.$deal->getTitle()."\n".
                '- Deal description: '.$deal->getDescription()."\n");

        $mailer->send($email);

        return $this->redirectToRoute('app_deal_detail', [
            'dealId' => $deal->getId(),
        ]);
    }

    #[Route('/deals/save', name: 'app_deals_save')]
    public function saveDeal(Request $request, EntityManagerInterface $entityManager): Response
    {
        $dealId = $request->query->get('dealId');
        $deal = $entityManager->getRepository(Deal::class)->find($dealId);
        $user = $this->getUser();

        if ($user instanceof User) {
            $user->addDealsSave($deal);
            $entityManager->persist($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_deal_detail', [
            'dealId' => $deal->getId(),
        ]);
    }
}
