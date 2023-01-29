<?php

namespace EESoapClient;
use Doctrine\ORM\EntityManager;
use Exception;
use model\Message;
use model\MessageContent;
use Monolog\Logger;
use PhpParser\Node\Expr\Array_;
use SoapClient;
use SoapFault;

/**
 *
 */
class SoapClientEE
{
    /**
     * @var string
     */
    private string $username;
    /**
     * @var string
     */
    private string $password;
    /**
     * @var Logger
     */
    private Logger $logger;
    /**
     * @var SoapClient
     */
    private SoapClient $client;
    /**
     * @var SQLHelper
     */
    private SQLHelper $SQLHelper;

    /**
     * @param Logger $logger
     * @param SQLHelper $SQLHelper
     */
    public function __construct(Logger $logger, SQLHelper $SQLHelper)
    {
        $this->logger = $logger;
        $this->SQLHelper = $SQLHelper;
    }

    /**
     *
     */
    public function __destruct()
    {
    }

    /**
     * @param $username
     * @param $password
     * @return void
     */
    public function createEESoapConnection($username, $password): void
    {
        $this->username = $username;
        $this->password = $password;
        $wsdl = "https://m2mconnect.ee.co.uk/orange-soap/services/MessageServiceByCountry?wsdl";
        try {
            $soap_client_parameters = ['trace' => true, 'exceptions' => true];
            $client = new SoapClient($wsdl, $soap_client_parameters);
            $this->client = $client;
        } catch (SoapFault $exception) {
            $this->logger->error($exception->getMessage());
        }
    }

    /**
     * @param int $count
     * @param string $deviceMsisdn
     * @param string $countryCode
     * @return array
     */
    public function peekMessages(int $count, string $deviceMsisdn, string $countryCode) : array
    {
        $message_list = [];

        try {
            $webservice_call_result = $this->client->peekMessages($this->username, $this->password, $count, $deviceMsisdn, $countryCode);

            if ($webservice_call_result == null) {
                $this->logger->warning("SOAP Request peekMessages returned null!");
                return [];
            } else {
                foreach ($webservice_call_result as $value) {
                    $xml = simplexml_load_string($value);
                    if (!$xml){
                        $this->logger->error("Failed to parse XML!");
                    }
                    // Map SimpleXmlElement Object to Message.
                    $message = new Message();
                    $message->setSourcemsisdn($xml->sourcemsisdn->__toString());
                    $message->setDestinationmsisdn($xml->destinationmsisdn->__toString());
                    $message->setReceivedtime($xml->receivedtime->__toString());
                    $message->setBearer($xml->bearer->__toString());
                    $message->setMessageref($xml->messageref->__toString());

                    $messageContent = new MessageContent();
                    $messageContent->setTeamName(strtoupper($xml->message->TeamName->__toString()));
                    $messageContent->setHeaterTemperature(intval($xml->message->HeaterTemperature->__toString()));
                    $messageContent->setFanState(strtolower($xml->message->FanState->__toString()));
                    $messageContent->setKeypadValue(intval($xml->message->KeypadValue->__toString()));
                    $messageContent->setSwitchOneState(strtolower($xml->message->SwitchOneState->__toString()));
                    $messageContent->setSwitchTwoState(strtolower($xml->message->SwitchTwoState->__toString()));
                    $messageContent->setSwitchThreeState(strtolower($xml->message->SwitchThreeState->__toString()));
                    $messageContent->setSwitchFourState(strtolower($xml->message->SwitchFourState->__toString()));

                    $message->setMessageContent($messageContent);
                    if(!$message->isValidMessage()){
                        $this->logger->warning("Message from " . $message->getSourcemsisdn() . "received at " . $message ->getReceivedtime() . " is invalid it will be ignored!");
                        continue;
                    }
                    if(!$messageContent->isValidMessageContent()){
                        $this->logger->warning("Message content from " . $message->getSourcemsisdn() . "received at " . $message ->getReceivedtime() . " is invalid it will be ignored!");
                        continue;
                    }
                    if (!$this->SQLHelper->isDuplicateMessage($message)){
                        $this->SQLHelper->persistMessage($message);
                        $this->sendMessageReceivedConfirmation($message);
                    }

                    $message_list[] = $message;
                }
            }
            $this->SQLHelper->flush();
        } catch (Exception $exception) {
            $this->logger->error($exception->getMessage());
        }

        return $message_list;
    }

    /**
     * @param Message $message
     * @return void
     */
    private function sendMessageReceivedConfirmation(Message $message){
        $messageToSend = "Your message sent at " . $message->getReceivedtime() . " has been processed.";
        $webservice_call_result = $this->client->sendMessage($this->username, $this->password, $message->getSourcemsisdn(), $messageToSend, false, "SMS");
        $this->logger->info("Message received at " . $message->getReceivedtime() . " from " . $message->getSourcemsisdn() . " has been processed");
        if ($webservice_call_result == null) {
            $this->logger->warning("SOAP Request sendMessage failed!");
        }
    }

    /**
     * @param Message $message
     * @return bool
     */
    private function isDuplicateMessage(Message $message) : bool
    {
        // Get message by received time
        $existingMessage = $this->em->getRepository('Model\Message')->findBy(array('receivedtime' => $message->getReceivedtime()));
        if ($existingMessage == null){
            return false;
        }

        return true;
    }
}
