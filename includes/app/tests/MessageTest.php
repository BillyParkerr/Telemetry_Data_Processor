<?php


use model\Message;
use PHPUnit\Framework\TestCase;

class MessageTest extends TestCase
{
    private function createMessage($sourcemsisdn, $destinationmsisdn, $receivedtime, $bearer, $messageref) : Message{
        $message = new Message();
        $message->setSourcemsisdn($sourcemsisdn);
        $message->setDestinationmsisdn($destinationmsisdn);
        $message->setReceivedtime($receivedtime);
        $message->setBearer($bearer);
        $message->setMessageref($messageref);

        return $message;
    }

    /**
     * @dataProvider additionDataProvider
     */
    public function testValidMessage($sourcemsisdn, $destinationmsisdn, $receivedtime, $bearer, $messageref, bool $valid)
    {
        // Arrange
        $message = $this->createMessage($sourcemsisdn, $destinationmsisdn, $receivedtime, $bearer, $messageref);

        // Act
        $returnedValue = $message->isValidMessage();

        // Assert
        $this->assertEquals($valid, $returnedValue);
    }

    public function additionDataProvider(): array
    {
        return [
            [447516729581, 447817814149, "01/01/2022 12:00:00", "SMS", 1, true], // Valid test case
            [44751672958, 447817814149, "01/01/2022 12:00:00", "SMS", 1, false], // Contains invalid sourcemsisdn as it is 11 characters
            [547516729581, 447817814149, "01/01/2022 12:00:00", "SMS", 1, false], // Contains invalid sourcemsisdn as it doesnt start with 44
            [447516729581, 447817814140, "01/01/2022 12:00:00", "SMS", 1, false], // Contains invalid destinationmsisdn as isnt 447817814149
            [447516729581, 447817814149, "20220101 120000", "SMS", 1, false], // Invalid date format
            [447516729581, 447817814149, "01/01/2022 12:00:00", "MMS", 1, false], // Contains invalid bearer as it should always be SMS
            [447516729581, 447817814149, "01/01/2022 12:00:00", "MMS", -1, false], // Contains invalid messageref as it should always be more than 0
            [447516729581, 447817814149, "01/01/2022 12:00:00", "MMS", 10000, false], // Contains invalid messageref as it should always less than 9999
        ];
    }
}
