<?php

namespace AppBundle\EventListener;

use Doctrine\ORM\EntityManager;
use Vich\UploaderBundle\Event\Event;

class RemovedFileListener
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
     * Make sure a file entity object is removed after the file is deleted.
     *
     * @param Event $event
     *
     * @throws \Doctrine\ORM\ORMInvalidArgumentException
     */
    public function onPostRemove(Event $event)
    {
        $removedFile = $event->getObject();
        $this->em->remove($removedFile);
    }
}
