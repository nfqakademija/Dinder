<?php

namespace AppBundle\Repository;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;

/**
 * HistoryRepository
 *
 */
class HistoryRepository extends EntityRepository
{
    /**
     * Mark all user history entries as seen
     *
     * @param User $user
     *
     * @return void
     */
    public function markSeen(User $user): void
    {
        $now = new \DateTime();

        $this->createQueryBuilder('h')
            ->update()
            ->set('h.seen', '?1')
            ->where('h.user = :user')
            ->setParameters([
                '1' => $now,
                'user' => $user,
            ])
            ->getQuery()
            ->getResult();
    }
}
