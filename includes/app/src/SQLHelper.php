<?php

namespace EESoapClient;

use Doctrine\ORM\EntityManager;
use model\Message;
use Monolog\Logger;

/**
 *
 */
class SQLHelper
{
    /**
     * @var Logger
     */
    private Logger $logger;
    /**
     * @var EntityManager
     */
    private EntityManager $em;

    /**
     * @param Logger $logger
     * @param EntityManager $em
     */
    public function __construct(Logger $logger, EntityManager $em)
    {
        $this->logger = $logger;
        $this->em = $em;
    }

    /**
     * @param Message $message
     * @return bool
     */
    public function isDuplicateMessage(Message $message) : bool
    {
        // Get message by received time
        $existingMessage = $this->em->getRepository('Model\Message')->findBy(array('receivedtime' => $message->getReceivedtime()));
        if ($existingMessage == null){
            return false;
        }

        return true;
    }

    /**
     * @param Message $message
     * @return void
     * @throws \Doctrine\ORM\Exception\ORMException
     */
    public function persistMessage(Message $message){
        $this->em->persist($message->getMessageContent());
        $this->em->persist($message);
    }

    /**
     * @return array
     */
    public function getAllStoredMessages() : array
    {
        return $this->em->getRepository('Model\Message')->findAll();
    }


    /**
     * @return void
     * @throws \Doctrine\ORM\Exception\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function flush()
    {
        $this->em->flush();
    }
}