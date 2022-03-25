<?php

namespace AltiriaSmsPhpClient\Test;

use AltiriaSmsPhpClient\AltiriaClient;
use AltiriaSmsPhpClient\AltiriaModelTextMessage;
use AltiriaSmsPhpClient\Exception\AltiriaGwException;
use PHPUnit\Framework\TestCase;


/**
 * Running this test suite supposes a consumption of credits (between 3 and 5 credits).
 */
class TestAltiriaSmsPhpClientSendSmsHttp extends TestCase
{
    //configurable parameters
    public $login = 'user@mydomain.com';
    public $password = 'mypassword';
    public $apiKey = 'XXXXXXXX';
    public $apiSecret = 'YYYYYYYY';
    //set to null if there is no sender
    public $sender = 'mySender';
    public $debug = false;
    public $destination = '346XXXXXXXX';

    /**
     * Only mandatory parameters are sent.
     */
    public function testOkMandatoryParams()
    {
        $message = 'Lorem Ipsum is simply dummy text';

        $client = new AltiriaClient($this->login, $this->password);
        $client->setDebug($this->debug);
        $textMessage = new AltiriaModelTextMessage($this->destination, $message);
        $response = $client-> sendSms($textMessage);
        
        self::assertSame(200, $response->getStatusCode());
        $body = $response->getBody();
        
        $json = json_decode($body);
        $status = $json->status;
        self::assertSame('000', $status);
        self::assertSame($this->destination, $json->details[0]->destination);
        self::assertSame('000', $json->details[0]->status);
    }

    /**
     * All params are sent.
     * Features:
     * - apikey authentication
     * - sender
     * - delivery confirmation with identifier
     * - concatenated
     * - set unicode encoding
     * - request delivery certificate
     */
    public function testOkAllParams()
    {
        $message = 'Lorem Ipsum is simply dummy text of the printing and typesetting industry €';
        $idAck = 'myAlias';
        $encoding = 'unicode';
        
        $client = new AltiriaClient($this->apiKey, $this->apiSecret, true);
        $client->setDebug($this->debug);
        $textMessage = new AltiriaModelTextMessage($this->destination, $message, $this->sender);
        
        //You can also assign the sender here
        //$textMessage->setSenderId($this->sender);
        
        // Need to configure a callback URL to use it. Contact comercial@altiria.com.
        //$textMessage->setAck(true);
        //$textMessage->setIdAck($idAck);
        
        $textMessage->setConcat(true);
        $textMessage->setEncoding($encoding);
        
        //If it is uncommented, additional credit will be consumed.
        //$textMessage->setCertDelivery(true);

        $response = $client-> sendSms($textMessage);
        
        self::assertSame(200, $response->getStatusCode());
        $body = $response->getBody();
        $json = json_decode($body);
        $status = $json->status;
        self::assertSame('000', $status);
        
        self::assertSame($this->destination.'(0)', $json->details[0]->destination);
        self::assertSame('000', $json->details[0]->status);
        
        //Uncomment if idAck is used.
        //self::assertSame($idAck, $json->details[0]->idAck);

        self::assertSame($this->destination.'(1)', $json->details[1]->destination);
        self::assertSame('000', $json->details[1]->status);

        //Uncomment if idAck is used.
        //self::assertSame($idAck, $json->details[1]->idAck);
    }

    /**
     * Invalid credentials.
     */
    public function testErrorInvalidCredentials()
    {
        try {
            $message = 'Lorem Ipsum is simply dummy text';

            $client = new AltiriaClient('unknown', $this->password);
            $client->setDebug($this->debug);
            $textMessage = new AltiriaModelTextMessage($this->destination, $message);
            $client-> sendSms($textMessage);
            $this->fail('AltiriaGwException should have been thrown');
  
        } catch (\AltiriaSmsPhpClient\Exception\AltiriaGwException $exception) {
            self::assertSame('AUTHENTICATION_ERROR', $exception->getMessage());
            self::assertSame('020', $exception->getStatus());
        }
    }

    /**
     * The destination parameter is invalid.
     */
    public function testErrorInvalidDestination()
    {
        try {
            $message = 'Lorem Ipsum is simply dummy text';
            $destination = 'invalid';

            $client = new AltiriaClient($this->login, $this->password);
            $client->setDebug($this->debug);
            $textMessage = new AltiriaModelTextMessage($destination, $message);
            $client-> sendSms($textMessage);
            $this->fail('AltiriaGwException should have been thrown');
        
        } catch (\AltiriaSmsPhpClient\Exception\AltiriaGwException $exception) {
            self::assertSame('INVALID_DESTINATION', $exception->getMessage());
            self::assertSame('015', $exception->getStatus());
        }
    }

    /**
     * The message parameter is empty.
     */
    public function testErrorEmptyMessage()
    {
        try {
            $message = '';

            $client = new AltiriaClient($this->login, $this->password);
            $client->setDebug($this->debug);
            $textMessage = new AltiriaModelTextMessage($this->destination, $message);
            $client-> sendSms($textMessage);
            $this->fail('AltiriaGwException should have been thrown');
        
        } catch (\AltiriaSmsPhpClient\Exception\AltiriaGwException $exception) {
            self::assertSame('EMPTY_MESSAGE', $exception->getMessage());
            self::assertSame('017', $exception->getStatus());
        }
    }

}
