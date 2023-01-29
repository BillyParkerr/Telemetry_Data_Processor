<?php

namespace EESoapClient;

use Doctrine\ORM\EntityManager;
use model\Message;
use Monolog\Logger;

class SQLHelper
{
    private Logger $logger;
    private EntityManager $em;

    public function __construct(Logger $logger, EntityManager $em)
    {
        $this->logger = $logger;
        $this->em = $em;
    }

    public function isDuplicateMessage(Message $message) : bool
    {
        // Get message by received time
        $existingMessage = $this->em->getRepository('Model\Message')->findBy(array('receivedtime' => $message->getReceivedtime()));
        if ($existingMessage == null){
            return false;
        }

        return true;
    }

    public function persistMessage(Message $message){
        $this->em->persist($message->getMessageContent());
        $this->em->persist($message);
    }

    public function getAllStoredMessages() : array
    {
        return $this->em->getRepository('Model\Message')->findAll();
    }


    public function flush()
    {
        $this->em->flush();
    }
}