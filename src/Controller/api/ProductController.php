<?php

namespace App\Controller\api;

use App\Entity\Comment;
use App\Entity\Deal;
use App\Entity\PromoCode;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    #[Route('/api/products/week', name: 'app_product_week')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $firstDayOfWeek = (new \DateTime())->setISODate((new \DateTime())->format('o'), (new \DateTime())->format('W'))->setTime(0, 0, 0);

        $lastDayOfWeek = clone $firstDayOfWeek;
        $lastDayOfWeek->modify('+6 days')->setTime(23, 59, 59);

        $deals = $entityManager->getRepository(Deal::class)->findDealsByDateRange($firstDayOfWeek, $lastDayOfWeek);
        $promocodes = $entityManager->getRepository(PromoCode::class)->findDealsByDateRange($firstDayOfWeek, $lastDayOfWeek);

        $combinedCollection = array_merge($deals, $promocodes);

        usort($combinedCollection, function ($a, $b) {
            return $b->getPublicationDatetime() <=> $a->getPublicationDatetime();
        });

        return $this->json($deals);
    }

    #[Route('/api/private/deals/save', name: 'app_user_deals_save')]
    public function getUserDealsSave(EntityManagerInterface $entityManager, Request $request): Response
    {
        $jsonData = json_decode($request->getContent(), true);
        $email = $jsonData['email'];
        $password = $jsonData['password'];
        $product = null;
        $data = [];

        $user = $entityManager->getRepository(User::class)->findUserByEmailAndPassword($email, $password);
        if (null != $user) {
            $userDealsSave = $user->getDealsSave()->map(function (Deal $deal) {
                return [
                    'id' => $deal->getId(),
                    'hotLevel' => $deal->getHotLevel(),
                    'publicationDatetime' => $deal->getPublicationDatetime(),
                    'expirationDatetime' => $deal->getExpirationDatetime(),
                    'link' => $deal->getLink(),
                    'title' => $deal->getTitle(),
                    'description' => $deal->getDescription(),
                    'promoCode' => $deal->getPromoCode(),
                    'price' => $deal->getPrice(),
                    'usualPrice' => $deal->getUsualPrice(),
                    'shippingCost' => $deal->getShippingCost(),
                    'freeDelivery' => $deal->isFreeDelivery(),
                    'comments' => $deal->getComments()->map(function (Comment $comment) {
                        return [
                            'id' => $comment->getId(),
                            'content' => $comment->getContent(),
                            'datetime' => $comment->getDatetime(),
                            'utilisateur' => $comment->getUtilisateur()->getPseudo(),
                        ];
                    })->toArray(),
                    'author' => $deal->getAuthor()->getPseudo(),
                    'groupDeal' => $deal->getGroupDeal(),
                ];
            })->toArray();

            $userPromoCodesSave = $user->getPromoCodesSave()->map(function (PromoCode $promoCode) {
                return [
                    'id' => $promoCode->getId(),
                    'hotLevel' => $promoCode->getHotLevel(),
                    'publicationDatetime' => $promoCode->getPublicationDatetime(),
                    'expirationDatetime' => $promoCode->getExpirationDatetime(),
                    'link' => $promoCode->getLink(),
                    'title' => $promoCode->getTitle(),
                    'description' => $promoCode->getDescription(),
                    'promoCodeValue' => $promoCode->getPromoCodeValue(),
                    'comments' => $promoCode->getComments()->map(function (Comment $comment) {
                        return [
                            'id' => $comment->getId(),
                            'content' => $comment->getContent(),
                            'datetime' => $comment->getDatetime(),
                            'utilisateur' => $comment->getUtilisateur()->getPseudo(),
                        ];
                    })->toArray(),
                    'author' => $promoCode->getAuthor()->getPseudo(),
                    'groupDeal' => $promoCode->getGroupDeal(),
                    'typeOfReduction' => $promoCode->getTypeOfReduction(),
                ];
            })->toArray();

            $data['dealsSave'] = $userDealsSave;
            $data['promoCodesSave'] = $userPromoCodesSave;
        }

        return $this->json($data);
    }
}
