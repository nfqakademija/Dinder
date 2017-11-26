<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Item;
use AppBundle\Entity\Match;
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
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $matches = $em->getRepository(Match::class)->findMatchesByUser($user);

        return $this->render('match/index.html.twig', array(
            'matches' => $matches,
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

        $currentUser = $this->get('security.token_storage')->getToken()->getUser();
        if ($currentUser !== $ownedItem->getUser()) {
            throw $this->createAccessDeniedException("It's not your item. Please stop cheating!");
        }

        $ownedItem
            ->setStatus(Item::STATUS_TRADED)
            ->setUser($offeredItem->getUser());

        $offeredItem
            ->setStatus(Item::STATUS_TRADED)
            ->setUser($currentUser);

        $em = $this->getDoctrine()->getManager();
        $em->remove($match);

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
        $currentUser = $this->get('security.token_storage')->getToken()->getUser();
        if ($currentUser !== $match->getItemRespondent()->getUser()) {
            throw $this->createAccessDeniedException("It's not your item. Please stop cheating!");
        }

        $match->setStatus(Match::STATUS_DECLINED);
        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('match_index');
    }
}
