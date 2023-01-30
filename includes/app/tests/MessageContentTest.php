<?php


use model\MessageContent;
use PHPUnit\Framework\TestCase;

class MessageContentTest extends TestCase
{
    private function createMessageContent($teamName, $heaterTemperature, $fanState, $keypadValue,
                                          $switchOneState, $switchTwoState, $switchThreeState,
                                          $switchFourState) : MessageContent
    {
        $messageContent = new MessageContent();
        $messageContent->setTeamName($teamName);
        $messageContent->setHeaterTemperature($heaterTemperature);
        $messageContent->setFanState($fanState);
        $messageContent->setKeypadValue($keypadValue);
        $messageContent->setSwitchOneState($switchOneState);
        $messageContent->setSwitchTwoState($switchTwoState);
        $messageContent->setSwitchThreeState($switchThreeState);
        $messageContent->setSwitchFourState($switchFourState);

        return $messageContent;
    }

    /**
     * @dataProvider additionDataProvider
     */
    public function testValidMessageContent($teamName, $heaterTemperature, $fanState, $keypadValue,
                                            $switchOneState, $switchTwoState, $switchThreeState,
                                            $switchFourState, bool $valid)
    {
        // Arrange
        $messageContent = $this->createMessageContent($teamName, $heaterTemperature, $fanState, $keypadValue,
                                                    $switchOneState, $switchTwoState, $switchThreeState,
                                                    $switchFourState);

        // Act
        $returnedValue = $messageContent->isValidMessageContent();

        // Assert
        $this->assertEquals($valid, $returnedValue);
    }

    public function additionDataProvider(): array
    {
        return [
            ["22-3110-AH", 23, "reverse", 224, "on", "on", "off", "on", true], // Valid test case
            ["22-3110-AH", 23, "forward", 224, "off", "off", "on", "off", true], // Valid test case
            ["22-3110-AD", 23, "reverse", 224, "on", "on", "off", "on", false], // Invalid team name must be 22-3110-AH
            ["22-3110-AH", -10, "reverse", 224, "on", "on", "off", "on", false], // Invalid heaterTemperature must be positive
            ["22-3110-AH", 2147483690, "reverse", 224, "on", "on", "off", "on", false], // Invalid heaterTemperature must be less than 2147483647
            ["22-3110-AH", 23, "backwards", 224, "on", "on", "off", "on", false], // Invalid fan state must be forward or reverse
            ["22-3110-AH", 23, "reverse", -10, "on", "on", "off", "on", false], // Invalid keypad value must be positive
            ["22-3110-AH", 23, "reverse", 2147483690, "on", "on", "off", "on", false], // Invalid keypad value must be less than 2147483647
            ["22-3110-AH", 23, "reverse", 224, "positive", "on", "off", "on", false], // Invalid switches must be on or off
            ["22-3110-AH", 23, "reverse", 224, "on", "negative", "off", "on", false], // Invalid switches must be on or off
            ["22-3110-AH", 23, "reverse", 224, "on", "off", "negative", "on", false], // Invalid switches must be on or off
            ["22-3110-AH", 23, "reverse", 224, "on", "off", "on", "positive", false], // Invalid switches must be on or off
        ];
    }
}
