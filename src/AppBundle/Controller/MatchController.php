<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Item;
use AppBundle\Entity\Match;
use AppBundle\Entity\History;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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
        $receivedOffersMatches = $em->getRepository(Match::class)->findMatchesByRespondent($user);
        $receivedOffers = $em->getRepository(Item::class)->findItemsByMatchRespondent($user);
        $sentOffers = $em->getRepository(Item::class)->findItemsByMatchOwner($user);
        $declinedOffers = $em->getRepository(Item::class)->findItemsByMatchOwner($user, Match::STATUS_DECLINED);

        $response = $this->render('match/index.html.twig', array(
            'received_offers' => $receivedOffers,
            'declined_offers' => $declinedOffers,
            'sent_offers' => $sentOffers,
        ));

        if ($receivedOffersMatches) {
            $this->markMatchesAsSeen($receivedOffersMatches);
        }

        return $response;
    }

    /**
     * @Route("/{id}/trade", name="match_trade")
     *
     * @Method("PUT")
     *
     * @param Request $request
     * @param Match $match
     *
     * @return Response
     */
    public function tradeAction(Request $request, Item $item): Response
    {
        if (!$this->isCsrfTokenValid($item->getId(), $request->get('_token'))) {
            throw $this->createAccessDeniedException('CSRF token is invalid');
        }

        $itemRespondentId = $request->get('respondent', false);

        if (!$itemRespondentId) {
            throw $this->createAccessDeniedException("It's not your item. Please stop cheating!");
        }

        $em = $this->getDoctrine()->getManager();

        $itemRespondent = $em->getRepository(Item::class)->find($itemRespondentId);

        if ($this->getUser() !== $itemRespondent->getUser()) {
            throw $this->createAccessDeniedException("It's not your item. Please stop cheating!");
        }

        $match = $em->getRepository(Match::class)->findOneBy([
            'itemOwner' => $item,
            'itemRespondent' => $itemRespondent,
        ]);

        $ownedItem = $match->getItemRespondent();
        $offeredItem = $match->getItemOwner();

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

        return new JsonResponse();
    }

    /**
     * @Route("/{id}/decline", name="match_decline")
     *
     * @Method("PUT")
     *
     * @param Request $request
     * @param Match $match
     *
     * @return Response
     */
    public function declineAction(Request $request, Item $item): Response
    {
        if (!$this->isCsrfTokenValid($item->getId(), $request->get('_token'))) {
            throw $this->createAccessDeniedException('CSRF token is invalid');
        }

        $itemRespondentId = $request->get('respondent', false);

        if (!$itemRespondentId) {
            throw $this->createAccessDeniedException("It's not your item. Please stop cheating!");
        }

        $em = $this->getDoctrine()->getManager();

        $itemRespondent = $em->getRepository(Item::class)->find($itemRespondentId);

        if ($this->getUser() !== $itemRespondent->getUser()) {
            throw $this->createAccessDeniedException("It's not your item. Please stop cheating!");
        }

        $match = $em->getRepository(Match::class)->findOneBy([
            'itemOwner' => $item,
            'itemRespondent' => $itemRespondent,
        ]);

        $match->setStatus(Match::STATUS_DECLINED);
        $this->getDoctrine()->getManager()->flush();

        return $this->forward('AppBundle:Item:owner', [
            'id' => $itemRespondentId
        ]);
    }

    /**
     * @Route("/{id}/remove", name="match_remove")
     *
     * @Method("DELETE")
     *
     * @param Request $request
     * @param Match $match
     *
     * @return Response
     */
    public function removeAction(Request $request, Item $item): Response
    {
        if (!$this->isCsrfTokenValid($item->getId(), $request->get('_token'))) {
            throw $this->createAccessDeniedException('CSRF token is invalid');
        }

        $itemOwnerId = $request->get('owner', false);

        if (!$itemOwnerId) {
            throw $this->createAccessDeniedException("It's not your item. Please stop cheating!");
        }

        $em = $this->getDoctrine()->getManager();

        $itemOwner = $em->getRepository(Item::class)->find($itemOwnerId);

        if ($this->getUser() !== $itemOwner->getUser()) {
            throw $this->createAccessDeniedException("It's not your item. Please stop cheating!");
        }

        $match = $em->getRepository(Match::class)->findOneBy([
            'itemOwner' => $itemOwner,
            'itemRespondent' => $item,
        ]);

        $em->remove($match);
        $em->flush();

        return $this->forward('AppBundle:Item:respondent', [
            'id' => $itemOwnerId
        ]);
    }

    /**
     * Mark all unseen matches as seen
     *
     * @param array $matches
     *
     * @return void
     */
    private function markMatchesAsSeen(array $matches): void
    {
        $em = $this->getDoctrine()->getManager();

        $now = new \DateTime();

        foreach ($matches as $match) {
            if (!$match->getSeen()) {
                $match->setSeen($now);

                $em->persist($match);
            }
        }

        $em->flush();
    }
}
