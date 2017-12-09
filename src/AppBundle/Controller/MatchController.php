<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Item;
use AppBundle\Entity\Match;
use AppBundle\Entity\History;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * Match controller.
 *
 * @Route("match")
 */
class MatchController extends Controller
{
    /**
     * Lists all match entities.
     *
     * @Route("/", name="match_index")
     *
     * @Method("GET")
     *
     * @return Response
     */
    public function indexAction(): Response
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $receivedOffers = $em->getRepository(Match::class)->findMatchesByRespondent($user);
        $sentOffers = $em->getRepository(Match::class)->findMatchesByOwner($user);
        $declinedOffers = $em->getRepository(Match::class)->findMatchesByOwner($user, Match::STATUS_DECLINED);

        return $this->render('match/index.html.twig', array(
            'received_offers' => $receivedOffers,
            'declined_offers' => $declinedOffers,
            'sent_offers' => $sentOffers,
        ));
    }

    /**
     * @Route("/{id}/trade", name="match_trade")
     *
     * @Method("GET")
     *
     * @param Match $match
     *
     * @return Response
     */
    public function tradeAction(Match $match): Response
    {
        $ownedItem = $match->getItemRespondent();
        $offeredItem = $match->getItemOwner();

        if ($this->getUser() !== $ownedItem->getUser()) {
            throw $this->createAccessDeniedException("It's not your item. Please stop cheating!");
        }

        $em = $this->getDoctrine()->getManager();

        $ownedItem
            ->setStatus(Item::STATUS_TRADED)
            ->setUser($offeredItem->getUser());

        $offeredItem
            ->setStatus(Item::STATUS_TRADED)
            ->setUser($this->getUser());

        $historyOwner = new History();
        $historyOwner->setItem($ownedItem);
        $historyOwner->setUser($ownedItem->getUser());
        $em->persist($historyOwner);

        $historyRespondent = new History();
        $historyRespondent->setItem($offeredItem);
        $historyRespondent->setUser($offeredItem->getUser());
        $em->persist($historyRespondent);
        $em->getRepository(Match::class)->deleteRelatedMathes($match);
        $em->flush();

        return $this->redirectToRoute('match_index');
    }

    /**
     * @Route("/{id}/decline", name="match_decline")
     *
     * @Method("GET")
     *
     * @param Match $match
     *
     * @return Response
     */
    public function declineAction(Match $match): Response
    {
        if ($this->getUser() !== $match->getItemRespondent()->getUser()) {
            throw $this->createAccessDeniedException("It's not your item. Please stop cheating!");
        }

        $match->setStatus(Match::STATUS_DECLINED);
        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('match_index');
    }

    /**
     * @Route("/{id}/remove", name="match_remove")
     *
     * @Method("DELETE")
     *
     * @param Match $match
     *
     * @return Response
     */
    public function removeAction(Match $match): Response
    {
        if ($this->getUser() !== $match->getItemOwner()->getUser()) {
            throw $this->createAccessDeniedException("It's not your item. Please stop cheating!");
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($match);
        $em->flush();

        return $this->redirectToRoute('match_index');
    }
}
