<?php

namespace AltiriaSmsPhpClient\Test;

use AltiriaSmsPhpClient\AltiriaClient;
use AltiriaSmsPhpClient\AltiriaModelTextMessage;
use AltiriaSmsPhpClient\Exception\AltiriaGwException;
use AltiriaSmsPhpClient\Exception\JsonException;
use PHPUnit\Framework\TestCase;

class TestAltiriaSmsPhpClientSendSmsAuth extends TestCase
{
    public $login = 'user@mydomain.com';
    public $password = 'mypassword';
    public $debug = false;
    public $destination = '346XXXXXXXX';

    /**
     * The login parameter is missed.
     */
    public function testErrorNoLogin()
    {
        try {
            $message = 'Lorem Ipsum is simply dummy text';
            
            $client = new AltiriaClient(null, $this->password);
            $client->setDebug($this->debug);
            $textMessage = new AltiriaModelTextMessage($this->destination, $message);
            $client-> sendSms($textMessage);
            $this->fail('JsonException should have been thrown');
  
        } catch (\AltiriaSmsPhpClient\Exception\JsonException $exception) {
            self::assertSame('LOGIN_NOT_NULL', $exception->getMessage());
        }
    }

    /**
     * The password parameter is missed.
     */
    public function testErrorNoPassword()
    {
        try {
            $message = 'Lorem Ipsum is simply dummy text';
            
            $client = new AltiriaClient($this->login, null);
            $client->setDebug($this->debug);
            $textMessage = new AltiriaModelTextMessage($this->destination, $message);
            $client-> sendSms($textMessage);
            $this->fail('JsonException should have been thrown');
  
        } catch (\AltiriaSmsPhpClient\Exception\JsonException $exception) {
            self::assertSame('PASSWORD_NOT_NULL', $exception->getMessage());
        }
    }

    /**
     * The destination parameter is missed.
     */
    public function testErrorNoDestination()
    {
        try {
            $message = 'Lorem Ipsum is simply dummy text';
            
            $client = new AltiriaClient($this->login, $this->password);
            $client->setDebug($this->debug);
            $textMessage = new AltiriaModelTextMessage(null, $message);
            $client-> sendSms($textMessage);
            $this->fail('AltiriaGwException should have been thrown');
  
        } catch (\AltiriaSmsPhpClient\Exception\AltiriaGwException $exception) {
            self::assertSame('INVALID_DESTINATION', $exception->getMessage());
            self::assertSame('015', $exception->getStatus());
        }
    }

    /**
     * The message parameter is missed.
     */
    public function testErrorNoMessage()
    {
        try {
            $client = new AltiriaClient($this->login, $this->password);
            $client->setDebug($this->debug);
            $textMessage = new AltiriaModelTextMessage($this->destination, null);
            $client-> sendSms($textMessage);
            $this->fail('JsonException should have been thrown');
  
        } catch (\AltiriaSmsPhpClient\Exception\AltiriaGwException $exception) {
            self::assertSame('EMPTY_MESSAGE', $exception->getMessage());
            self::assertSame('017', $exception->getStatus());
        }
    }
}