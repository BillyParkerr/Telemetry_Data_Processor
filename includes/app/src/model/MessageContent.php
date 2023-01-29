<?php
namespace model;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="message_content")
 */
class MessageContent
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
    private $teamName;

    /**
     * @ORM\Column(type="integer")
     */
    private $heaterTemperature;

    /**
     * @ORM\Column(type="string")
     */
    private $fanState;

    /**
     * @ORM\Column(type="integer")
     */
    private $keypadValue;

    /**
     * @ORM\Column(type="string")
     */
    private $switchOneState;

    /**
     * @ORM\Column(type="string")
     */
    private $switchTwoState;

    /**
     * @ORM\Column(type="string")
     */
    private $switchThreeState;

    /**
     * @ORM\Column(type="string")
     */
    private $switchFourState;

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
    public function getTeamName()
    {
        return $this->teamName;
    }

    /**
     * @param mixed $teamName
     */
    public function setTeamName(string $teamName): void
    {
        $this->teamName = $teamName;
    }

    /**
     * @return mixed
     */
    public function getHeaterTemperature()
    {
        return $this->heaterTemperature;
    }

    /**
     * @param mixed $heaterTemperature
     */
    public function setHeaterTemperature(int $heaterTemperature): void
    {
        $this->heaterTemperature = $heaterTemperature;
    }

    /**
     * @return mixed
     */
    public function getFanState()
    {
        return $this->fanState;
    }

    /**
     * @param mixed $fanState
     */
    public function setFanState(string $fanState): void
    {
        $this->fanState = $fanState;
    }

    /**
     * @return mixed
     */
    public function getKeypadValue()
    {
        return $this->keypadValue;
    }

    /**
     * @param mixed $keypadValue
     */
    public function setKeypadValue(int $keypadValue): void
    {
        $this->keypadValue = $keypadValue;
    }

    /**
     * @return mixed
     */
    public function getSwitchOneState()
    {
        return $this->switchOneState;
    }

    /**
     * @param mixed $switchOneState
     */
    public function setSwitchOneState(string $switchOneState): void
    {
        $this->switchOneState = $switchOneState;
    }

    /**
     * @return mixed
     */
    public function getSwitchTwoState()
    {
        return $this->switchTwoState;
    }

    /**
     * @param mixed $switchTwoState
     */
    public function setSwitchTwoState(string $switchTwoState): void
    {
        $this->switchTwoState = $switchTwoState;
    }

    /**
     * @return mixed
     */
    public function getSwitchThreeState()
    {
        return $this->switchThreeState;
    }

    /**
     * @param mixed $switchThreeState
     */
    public function setSwitchThreeState(string $switchThreeState): void
    {
        $this->switchThreeState = $switchThreeState;
    }

    /**
     * @return mixed
     */
    public function getSwitchFourState()
    {
        return $this->switchFourState;
    }

    /**
     * @param mixed $switchFourState
     */
    public function setSwitchFourState(string $switchFourState): void
    {
        $this->switchFourState = $switchFourState;
    }

    public function isValidMessageContent() : bool{
        if(!$this->isValidTeamName()) return false;
        if(!$this->isValidSwitchOneState()) return false;
        if(!$this->isValidSwitchTwoState()) return false;
        if(!$this->isValidSwitchThreeState()) return false;
        if(!$this->isValidSwitchFourState()) return false;
        if(!$this->isValidFanState()) return false;
        if(!$this->isValidHeaterTemperature()) return false;
        if(!$this->isValidKeypadValue()) return false;

        return true;
    }

    private function isValidTeamName() : bool{
        // Team name should always be 22-3110-AH
        if ($this->teamName != "22-3110-AH"){
            return false;
        }

        return true;
    }

    private function isValidSwitchOneState() : bool{
        // Switches should always have a state of off or on
        return $this->switchOneState == "off" || $this->switchOneState == "on";
    }

    private function isValidSwitchTwoState() : bool{
        // Switches should always have a state of off or on
        return $this->switchTwoState == "off" || $this->switchTwoState == "on";

    }

    private function isValidSwitchThreeState() : bool{
        // Switches should always have a state of off or on
        return $this->switchThreeState == "off" || $this->switchThreeState == "on";
    }

    private function isValidSwitchFourState() : bool{
        // Switches should always have a state of off or on
        return $this->switchFourState == "off" || $this->switchFourState == "on";
    }

    private function isValidFanState() : bool{
        // Fan should always be reverse or forward
        return $this->fanState == "reverse" || $this->fanState == "forward";
    }

    private function isValidKeypadValue() : bool{
        // Keypad value should always be greater than 0 and less than 2147483647 in order to fit within an int(10).
        if (is_numeric($this->keypadValue) && floor($this->keypadValue) == $this->keypadValue && $this->keypadValue >= 0 && $this->keypadValue <= 2147483647) {
            return true;
        }

        return false;
    }

    private function isValidHeaterTemperature() : bool{
        // heater temperature should always be greater than 0 and less than 2147483647 in order to fit within an int(10).
        if (is_numeric($this->keypadValue) && floor($this->keypadValue) == $this->keypadValue && $this->keypadValue >= 0 && $this->keypadValue <= 2147483647) {
            return true;
        }

        return false;
    }
}