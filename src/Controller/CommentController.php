<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Deal;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{
    #[Route('/comment/deal/add', name: 'app_add_deal_comment')]
    public function addComment(Request $request, EntityManagerInterface $entityManager): Response
    {
        $comment = new Comment();
        $content = $request->request->get('content');
        $dealId = $request->query->get('dealId');
        $deal = $entityManager->getRepository(Deal::class)->find($dealId);

        $comment->setDatetime(new \DateTime());
        $comment->setDeal($deal);
        $comment->setContent($content);
        $user = $this->getUser();
        if ($user instanceof User) {
            $comment->setUtilisateur($user);
        }

        $entityManager->persist($comment);
        $entityManager->flush();

        return $this->redirectToRoute('app_deal_detail', [
            'dealId' => $dealId,
        ]);
    }
}
