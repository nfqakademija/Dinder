<?php

namespace AppBundle\Utils;

use AppBundle\Entity\Match;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;

class HistoryUnseenCount
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * Return user unseen history entries
     *
     * @param User $user
     *
     * @return int
     */
    public function countUnseenItems(User $user)
    {
        $result = 0;

        foreach ($user->getHistories() as $history) {
            if (!$history->getSeen()) {
                $result++;
            }
        }

        return $result;
    }

    /**
     * Return user unseen match entries
     *
     * @param User $user
     *
     * @return int
     */
    public function countUnseenMatches(User $user)
    {
        $result = 0;

        foreach ($user->getItems() as $item) {
            foreach ($item->getMatchesResponseItem() as $match) {
                if (!$match->getSeen() && $match->getStatus() === Match::STATUS_ACCEPTED) {
                    $result++;
                }
            }
        }

        return $result;
    }
}
