<?php

namespace AltiriaSmsPhpClient;

use AltiriaSmsPhpClient\AltiriaModelTextMessage;
use AltiriaSmsPhpClient\Exception\GeneralAltiriaException;
use AltiriaSmsPhpClient\Exception\AltiriaGwException;
use AltiriaSmsPhpClient\Exception\ConnectionException;
use AltiriaSmsPhpClient\Exception\JsonException;
use Exception;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Ring\Client\CurlHandler;

/**
 * Altiria PHP client class for sending HTTP requests.
 */
class AltiriaClient
{
    protected $login;
    protected $passwd;
    protected $debug = false;
    protected $textMessage;
    protected $isApiKey;

    // connection timeout values are defined here
    protected $connectTimeout=3000;
    protected $maxConnectTimeout=10000;
    protected $minConnectTimeout=1000;

    // response timeout values are defined here
    protected $timeout=10000;
    protected $maxTimeout=30000;
    protected $minTimeout=1000;

    // API URL
    protected $urlBase='https://www.altiria.net:8443/apirest/ws';

    // Library name/version
    protected $source='lib-php-composer-1_0';

    /**
     * Constructor.
     * 
     * @param string  $login    user login
     * @param string  $password user password
     * @param boolean $isApiKey set to true if apikey is used
     * @param string  $timeout  (optional parameter) response timeout
     */
    public function __construct($login, $password, $isApiKey = false, $timeout = 10000)
    {
        $this->login = $login;
        $this->passwd = $password;
        $this->isApiKey = $isApiKey;
        $this->setTimeout($timeout);
    }

    /**
     * Get the login parameter.
     * 
     * @return $login parameter
     */
    private function _getLogin()
    {
        return $this->login;
    }

    /**
     * Get the password parameter.
     * 
     * @return $passwd parameter
     */
    private function _getPasswd()
    {
        return $this->passwd;
    }

    /**
     * Returns true if apikey authentication method is selected.
     * 
     * @return $isApiKey parameter
     */
    private function _getApiKey()
    {
        return $this->isApiKey;
    }
    
    /**
     * Show debugging logs.
     * 
     * @param boolean $debug if it is true debug is enabled
     */
    public function setDebug($debug)
    {
        $this->debug = $debug;
    }

    /**
     * Set the response timeout.
     * 
     * @param int $timeout milliseconds for response timeout
     */
    public function setTimeout($timeout)
    {
        if ($timeout > $this->maxTimeout) {
            $this->timeout = 10000;
        } elseif ($timeout < $this->minTimeout) {
            $this->timeout = 10000;
        } else {
            $this->timeout = $timeout;
        }
    }

    /**
     * Set the connection timeout.
     * 
     * @param int $connectTimeout milliseconds for connection timeout
     */
    public function setConnectTimeout($connectTimeout)
    {
        if ($connectTimeout > $this->maxConnectTimeout) {
            $this->connectTimeout = 3000;
        } elseif ($connectTimeout < $this->minConnectTimeout) {
            $this->connectTimeout = 3000;
        } else {
            $this->connectTimeout = $connectTimeout;
        }   
    }
    
    /**
     * Get the object AltiriaModelTextMessage.
     * 
     * @return AltiriaModelTextMessage $textMessage object
     */
    public function getTextMessage(): AltiriaModelTextMessage
    {
        return $this->textMessage;
    } 

    /**
     * Send a SMS.
     * 
     * @param AltiriaModelTextMessage $textMessage this object contains the SMS data. See AltiriaModelTextMessage class.
     * 
     * @return Response $response response object
     */
    public function sendSms(AltiriaModelTextMessage $textMessage): \GuzzleHttp\Psr7\Response
    {
        $response = null;
        try {
            if ($this->login == null) {
                error_log("ERROR: The login parameter is mandatory");
                throw new JsonException('LOGIN_NOT_NULL');
            }
            if ($this->passwd == null) {
                error_log("ERROR: The password parameter is mandatory");
                throw new JsonException('PASSWORD_NOT_NULL');
            }

            if ($textMessage->getDestination()!=null && !empty($textMessage->getDestination())) {
                $destination = array($textMessage->getDestination());
            } else {
                if ($this->debug) {
                    error_log("ERROR: The destination parameter is mandatory");
                }
                throw new AltiriaGwException('INVALID_DESTINATION', "015");
            }

            if ($textMessage->getMessage()==null || empty($textMessage->getMessage())) {
                if ($this->debug) {
                    error_log("ERROR: The message parameter is mandatory");
                }
                throw new AltiriaGwException('EMPTY_MESSAGE', "017");
            }

            $message = array(
                "msg" => $textMessage->getMessage()
            );

            if ($textMessage->getSenderId()!=null && !empty($textMessage->getSenderId())) {
                $message['senderId'] = $textMessage->getSenderId();
            }

            if ($textMessage->getAck()===true) {
                $message['ack'] = $textMessage->getAck();
            }
            
            if ($textMessage->getIdAck()!=null && !empty($textMessage->getIdAck())) {
                $message['idAck'] = $textMessage->getIdAck();
            }

            if ($textMessage->getConcat()===true) {
                $message['concat'] = $textMessage->getConcat();
            }

            if ($textMessage->getCertDelivery()===true) {
                $message['certDelivery'] = $textMessage->getCertDelivery();
            }

            if ($textMessage->getEncoding()==='unicode') {
                $message['encoding'] = $textMessage->getEncoding();
            }

            try {
                $loginKey = $this->_getApiKey() ? 'apikey' : 'login';
                $passwordKey = $this->_getApiKey() ? 'apisecret' : 'passwd';
                $client = new \GuzzleHttp\Client();
                $response = $client->request(
                    'POST', $this->urlBase.'/sendSms', [
                        'json' => ['credentials' => [ $loginKey => $this->_getLogin(), $passwordKey => $this->_getPasswd()],
                            'destination' => $destination,
                            'message' => $message,
                            'source' => $this->source],
                        'connect_timeout' => $this->connectTimeout/1000,       
                        'timeout' => $this->timeout/1000,
                        'debug' => $this->debug
                    ]
                );
            } catch (\GuzzleHttp\Exception\ClientException $exception) { //HTTP STATUS CODE !=200
                $response = $exception->getResponse();
            } catch (\GuzzleHttp\Exception\ConnectException $exception) {
                if ($exception->getMessage().strpos('timed', 0) != -1) {
                    if ($this->debug) {
                        error_log("ERROR: Response timeout");
                    }
                    throw new ConnectionException('RESPONSE_TIMEOUT');
                } else {
                    if ($this->debug) {
                        error_log("ERROR: Connection timeout");
                    }
                    throw new ConnectionException('CONNECTION_TIMEOUT');
                } 
            }

            $body = $response->getBody();
            $json = json_decode($body);
            if ($response->getStatusCode() != 200) {
                $message = $json->error;
                if ($this->debug) {
                    error_log("ERROR: Invalid request: ".$message);
                }
                throw new JsonException($message);
            } else {
                $status = $json->status;
                if ($status != '000') {                
                    $errorMessage = $this->getStatus($status);
                    if ($this->debug) {
                        error_log("ERROR: Invalid parameter. Error message: ".$errorMessage.", Status: ".$status);
                    }
                    throw new AltiriaGwException($errorMessage, $status);
                }
            }
        } catch (GeneralAltiriaException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            if ($this->debug) {
                error_log("ERROR: Unexpected error: ".$exception->getMessage());
            }
            throw new AltiriaGwException("GENERAL_ERROR", "001");
        }   
        return $response;
    }

    /**
     * Get the user credit.
     * 
     * @return String $response response object
     */
    public function getCredit(): String
    {
        $credit = null;
        try {
            if ($this->login == null) {
                error_log("ERROR: The login parameter is mandatory");
                throw new JsonException('LOGIN_NOT_NULL');
            }
            if ($this->passwd == null) {
                error_log("ERROR: The password parameter is mandatory");
                throw new JsonException('PASSWORD_NOT_NULL');
            }

            $response = null;
            try {
                $loginKey = $this->_getApiKey() ? 'apikey' : 'login';
                $passwordKey = $this->_getApiKey() ? 'apisecret' : 'passwd';
                $client = new \GuzzleHttp\Client();
                $response = $client->request(
                    'POST', $this->urlBase.'/getCredit', [
                        'json' => ['credentials' => [$loginKey => $this->_getLogin(), $passwordKey => $this->_getPasswd()],
                            'source' => $this->source ],
                        'connect_timeout' => $this->connectTimeout/1000,       
                        'timeout' => $this->timeout/1000,
                        'debug' => $this->debug
                    ]
                );
            } catch (\GuzzleHttp\Exception\ClientException $exception) {
                $response = $exception->getResponse();
            } catch (\GuzzleHttp\Exception\ConnectException $exception) {
                if ($exception->getMessage().strpos('timed', 0) != -1) {
                    if ($this->debug) {
                        error_log("ERROR: Response timeout");
                    }
                    throw new ConnectionException('RESPONSE_TIMEOUT');
                } else {
                    if ($this->debug) {
                        error_log("ERROR: Connection timeout");
                    }
                    throw new ConnectionException('CONNECTION_TIMEOUT');
                } 
            }

            $body = $response->getBody();
            $json = json_decode($body);
            if ($response->getStatusCode() != 200) {
                $message = $json->error;
                if ($this->debug) {
                    error_log("ERROR: Invalid request: ".$message);
                }
                throw new JsonException($message);
            } else {
                $status = $json->status;
                if ($status != '000') {                
                    $errorMessage = $this->getStatus($status);
                    if ($this->debug) {
                        error_log("ERROR: Invalid parameter. Error message: ".$errorMessage.", Status: ".$status);
                    }
                    throw new AltiriaGwException($errorMessage, $status);
                } else {
                    $credit = $json->credit;
                }
            }

        } catch (GeneralAltiriaException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            if ($this->debug) {
                error_log("ERROR: Unexpected error: ".$exception->getTrace());
            }
            throw new AltiriaGwException("GENERAL_ERROR", "001");
        }
        return $credit;
    }

    /**
     * Provide the status message through the status code.
     */
    public function getStatus($status) 
    {
        $errorMessage = 'GENERAL_ERROR';
        if ($status == '001') {
            $errorMessage = 'INTERNAL_SERVER_ERROR';
        } elseif ($status == '002') { 
            $errorMessage = 'SSL_PORT_ERROR';
        } elseif ($status == '010') { 
            $errorMessage = 'DESTINATION_FORMAT_ERROR';
        } elseif ($status == '013') { 
            $errorMessage = 'MESSAGE_IS_TOO_LONG';
        } elseif ($status == '014') { 
            $errorMessage = 'INVALID_HTTP_REQUEST_ENCODING';
        } elseif ($status == '015') { 
            $errorMessage = 'INVALID_DESTINATION';
        } elseif ($status == '016') { 
            $errorMessage = 'DUPLICATED_DESTINATION';
        } elseif ($status == '017') { 
            $errorMessage = 'EMPTY_MESSAGE';
        } elseif ($status == '018') { 
            $errorMessage = 'TOO_MANY_DESTINATIONS';
        } elseif ($status == '019') { 
            $errorMessage = 'TOO_MANY_MESSAGES';
        } elseif ($status == '020') { 
            $errorMessage = 'AUTHENTICATION_ERROR';
        } elseif ($status == '033') { 
            $errorMessage = 'INVALID_DESTINATION_SMS_PORT';
        } elseif ($status == '034') { 
            $errorMessage = 'INVALID_ORIGIN_SMS_PORT';
        } elseif ($status == '035') { 
            $errorMessage = 'INVALID_LANDING';
        } elseif ($status == '036') { 
            $errorMessage = 'LANDING_NOT_EXISTS';
        } elseif ($status == '037') { 
            $errorMessage = 'TOO_MANY_LANDINGS';
        } elseif ($status == '038') { 
            $errorMessage = 'SYNTAX_LANDING_ERROR';
        } elseif ($status == '039') { 
            $errorMessage = 'SYNTAX_WEB_PARAMS_ERROR';
        }
        return $errorMessage;
    }
    
}

