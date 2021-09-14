<?php

namespace AltiriaRestClient\Test;

use AltiriaSmsPhpClient\AltiriaClient;
use AltiriaSmsPhpClient\AltiriaModelTextMessage;
use AltiriaRestClient\Exception\ConnectionException;
use PHPUnit\Framework\TestCase;

class TestAltiriaSmsPhpClientSendSmsConnection extends TestCase
{
    public $login = 'user@altiria.com';
    public $password = 'mypassword';
    public $debug = false;
    public $destination = '346XXXXXXXX';

    /**
     * Checks the connection timeout parameter.
     */
    public function testErrorConnectionTimeout()
    {
        try {
            $message = 'Lorem Ipsum is simply dummy text';
            
            $client = new AltiriaClient($this->login, $this->password);
            $client->setDebug($this->debug);
            $client->setConnectTimeout(1);
            $textMessage = new AltiriaModelTextMessage($this->destination, $message);
            $client-> sendSms($textMessage);
            $this->fail('ConnectionException should have been thrown');
  
        } catch (\AltiriaSmsPhpClient\Exception\ConnectionException $exception) {
            self::assertSame('CONNECTION_TIMEOUT', $exception->getMessage());
        }
    }

    /**
     * Checks the timeout parameter. It is mandatory to add a sleep in the server.
     */
    public function testErrorTimeoutConstructor()
    {
        try {
            $message = 'Lorem Ipsum is simply dummy text';
            
            $client = new AltiriaClient($this->login, $this->password, 5000);
            $client->setDebug($this->debug);
            $textMessage = new AltiriaModelTextMessage($this->destination, $message);
            $client-> sendSms($textMessage);
            $this->fail('ConnectionException should have been thrown');
  
        } catch (\AltiriaSmsPhpClient\Exception\ConnectionException $exception) {
            self::assertSame('RESPONSE_TIMEOUT', $exception->getMessage());
        }
    }

    /**
     * Checks the timeout parameter. It is mandatory to add a sleep in the server.
     */
    public function testErrorTimeoutSetter()
    {
        try {
            $message = 'Lorem Ipsum is simply dummy text';
            
            $client = new AltiriaClient($this->login, $this->password);
            $client->setDebug($this->debug);
            $client->setTimeout(5000);
            $textMessage = new AltiriaModelTextMessage($this->destination, $message);
            $client-> sendSms($textMessage);
            $this->fail('ConnectionException should have been thrown');
  
        } catch (\AltiriaSmsPhpClient\Exception\ConnectionException $exception) {
            self::assertSame('RESPONSE_TIMEOUT', $exception->getMessage());
        }
    }
}