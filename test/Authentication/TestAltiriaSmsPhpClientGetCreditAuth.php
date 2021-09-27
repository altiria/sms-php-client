<?php

namespace AltiriaSmsPhpClient\Test;

use AltiriaSmsPhpClient\AltiriaClient;
use AltiriaSmsPhpClient\Exception\JsonException;
use PHPUnit\Framework\TestCase;

class TestAltiriaSmsPhpClientGetCreditAuth extends TestCase
{
    public $login = 'user@mydomain.com';
    public $password = 'mypassword';
    public $debug = false;

    /**
     * The login parameter is missed.
     */
    public function testErrorNoLogin()
    {
        try {            
            $client = new AltiriaClient(null, $this->password);
            $client->setDebug($this->debug);
            $client-> getCredit();
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
            $client = new AltiriaClient($this->login, null);
            $client->setDebug($this->debug);
            $client-> getCredit();
            $this->fail('JsonException should have been thrown');
  
        } catch (\AltiriaSmsPhpClient\Exception\JsonException $exception) {
            self::assertSame('PASSWORD_NOT_NULL', $exception->getMessage());
        }
    }
}