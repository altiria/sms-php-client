<?php

namespace AltiriaRestClient\Test;

use AltiriaSmsPhpClient\AltiriaClient;
use AltiriaSmsPhpClient\Exception\ConnectionException;
use PHPUnit\Framework\TestCase;

class TestAltiriaSmsPhpClientGetCreditConnection extends TestCase
{
    public $login = 'user@altiria.com';
    public $password = 'mypassword';
    public $debug = false;

    /**
     * Checks the connection timeout parameter.
     */
    public function testErrorConnectionTimeout()
    {
        try {
            $client = new AltiriaClient($this->login, $this->password);
            $client->setDebug($this->debug);
            $client->setConnectTimeout(1);
            $client-> getCredit();
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
            $client = new AltiriaClient($this->login, $this->password, 5000);
            $client->setDebug($this->debug);
            $client-> getCredit();
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
            $client = new AltiriaClient($this->login, $this->password);
            $client->setDebug($this->debug);
            $client->setTimeout(5000);
            $client-> getCredit();
            $this->fail('ConnectionException should have been thrown');
  
        } catch (\AltiriaSmsPhpClient\Exception\ConnectionException $exception) {
            self::assertSame('RESPONSE_TIMEOUT', $exception->getMessage());
        }
    }
}