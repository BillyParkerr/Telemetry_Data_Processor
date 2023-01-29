<?php

namespace model;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="message")
 */
class Message
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $sourcemsisdn;

    /**
     * @ORM\Column(type="string")
     */
    private $destinationmsisdn;

    /**
     * @ORM\Column(type="string")
     */
    private $receivedtime;

    /**
     * @ORM\Column(type="string")
     */
    private $bearer;

    /**
     * @ORM\Column(type="string")
     */
    private $messageref;

    /**
     * @ORM\ManyToOne(targetEntity="MessageContent")
     * @ORM\JoinColumn(name="messageContentId", referencedColumnName="id")
     */
    private $messageContent;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getSourcemsisdn()
    {
        return $this->sourcemsisdn;
    }

    /**
     * @param mixed $sourcemsisdn
     */
    public function setSourcemsisdn($sourcemsisdn): void
    {
        $this->sourcemsisdn = $sourcemsisdn;
    }

    /**
     * @return mixed
     */
    public function getDestinationmsisdn()
    {
        return $this->destinationmsisdn;
    }

    /**
     * @param mixed $destinationmsisdn
     */
    public function setDestinationmsisdn($destinationmsisdn): void
    {
        $this->destinationmsisdn = $destinationmsisdn;
    }

    /**
     * @return mixed
     */
    public function getReceivedtime()
    {
        return $this->receivedtime;
    }

    /**
     * @param mixed $receivedtime
     */
    public function setReceivedtime(string $receivedtime): void
    {
        $this->receivedtime = $receivedtime;
    }

    /**
     * @return mixed
     */
    public function getBearer()
    {
        return $this->bearer;
    }

    /**
     * @param mixed $bearer
     */
    public function setBearer(string $bearer): void
    {
        $this->bearer = $bearer;
    }

    /**
     * @return mixed
     */
    public function getMessageref()
    {
        return $this->messageref;
    }

    /**
     * @param mixed $messageref
     */
    public function setMessageref(int $messageref): void
    {
        $this->messageref = $messageref;
    }

    /**
     * @return mixed
     */
    public function getMessageContent()
    {
        return $this->messageContent;
    }

    /**
     * @param mixed $messageContent
     */
    public function setMessageContent(MessageContent $messageContent): void
    {
        $this->messageContent = $messageContent;
    }

    public function isValidMessage() : bool{
        if(!$this->isValidSourcemsisdn()) return false;
        if(!$this->isValidDestinationmsisdn()) return false;
        if(!$this->isValidReceivedtime()) return false;
        if(!$this->isValidBearer()) return false;
        if(!$this->isValidMessageRef()) return false;

        return true;
    }


    private function isValidSourcemsisdn() : bool{
        // Number should be 12 characters long
        if(strlen($this->sourcemsisdn) != 12){
            return false;
        }

        // The number should start with 44
        if(!str_starts_with($this->sourcemsisdn, '44')){
            return false;
        }

        return true;
    }

    private function isValidDestinationmsisdn() : bool{
        // Number should always be 447817814149
        if($this->destinationmsisdn != '447817814149'){
            return false;
        }

        return true;
    }

    private function isValidReceivedtime() : bool{
        // ensure date is in the correct format i.e. day/month/year hour:minute:second
        $format = "d/m/Y H:i:s";
        $date_obj = date_create_from_format($format, $this->receivedtime);
        if ($date_obj) {
            return true;
        } else {
            return false;
        }
    }

    private function isValidBearer() : bool{
        // the bearer should always be SMS
        if($this->bearer != "SMS"){
            return false;
        }

        return true;
    }

    private function isValidMessageRef() : bool{
        // check that message ref is positive and fits within an int(4)
        if (!is_numeric($this->messageref) || $this->messageref < 0 || $this->messageref > 9999) {
            return false;
        }
        return true;
    }

}