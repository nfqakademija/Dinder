<?php

namespace AppBundle\Utils;

use Doctrine\ORM\EntityManager;
use AppBundle\Entity\User;

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
    public function countUnseed(User $user)
    {
        $result = 0;

        foreach ($user->getHistories() as $history) {
            if (!$history->getSeen()) {
                $result++;
            }
        }

        return $result;
    }
}
