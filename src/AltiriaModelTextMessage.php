<?php

namespace AltiriaSmsPhpClient;

/**
 * This class contains the data of the SMS to send.
 */
class AltiriaModelTextMessage
{

    protected $destination;
    protected $message;
    protected $senderId;
    protected $ack = false;
    protected $idAck;
    protected $concat = false;
    protected $certDelivery = false;
    protected $encoding;

    /**
     * Constructor.
     * 
     * @param string $destination destination phone
     * @param string $message     SMS text
     * @param string $senderId  (optional parameter) sender id
     */
    public function __construct($destination, $message, $senderId = null) 
    {
        $this->destination = $destination;
        $this->message = $message;
        if ($senderId != null) {
            $this->senderId = $senderId;
        }
    }
    
    /**
     * Get the destination phone.
     * 
     * @return string $destination destination phone
     */
    public function getDestination()
    {
        return $this->destination;
    }
    
    /**
     * Set the destination phone parameter.
     * 
     * @param string $destination destination phone
     */
    public function setDestination($destination)
    {
        $this->destination = $destination;
    }
    
    /**
     * Get the SMS text.
     * 
     * @return string $message SMS text
     */
    public function getMessage()
    {
        return $this->message;
    }
    
    /**
     * Set the SMS text parameter.
     * 
     * @param string $message SMS text
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }
    
    /**
     * Get the sender id.
     * 
     * @return string sender sender id
     */
    public function getSenderId()
    {
        return $this->senderId;
    }
    
    /**
     * Set the sender id parameter.
     * 
     * @param string $senderId sender id
     */
    public function setSenderId($senderId)
    {
        $this->senderId = $senderId;
    }
    
    /**
     * Get the ack parameter.
     * 
     * @return boolean $ack if it is true ack is enabled
     */
    public function getAck()
    {
        return $this->ack;
    }
    
    /**
     * Set the ack parameter. It allows to certify the delivery of the SMS.
     * 
     * @param boolean $ack it is true ack is enabled
     */
    public function setAck($ack)
    {
        $this->ack = $ack;
    }
    
    /**
     * Get the id ack parameter.
     * 
     * @return string $idAck id ack
     */
    public function getIdAck()
    {
        return $this->idAck;
    }
    
    /**
     * Set the id ack parameter. It allows you to set a delivery alias.
     * 
     * @param string $idAck id ack
     */
    public function setIdAck($idAck)
    {
        $this->idAck = $idAck;
    }
    
    /**
     * Get the concat parameter.
     * 
     * @return boolean $concat if it is true ack is enabled
     */
    public function getConcat()
    {
        return $this->concat;
    }
    
    /**
     * Set the concat parameter.
     * 
     * @param boolean $concat if it is true ack is enabled
     */
    public function setConcat($concat)
    {
        $this->concat = $concat;
    }
    
    /**
     * Set the cert delivery parameter.
     * 
     * @return boolean $certDelivery if it is true ack is enabled
     */
    public function getCertDelivery()
    {
        return $this->certDelivery;
    }
    
    /**
     * Get the value of cert delivery parameter.
     * 
     * @param boolean $certDelivery if it is true ack is enabled
     */
    public function setCertDelivery($certDelivery)
    {
        $this->certDelivery = $certDelivery;
    }
    
    /**
     * Get the encoding parameter. By default 'ISO8859-1' is used.
     * 
     * @return string $encoding SMS encoding
     */
    public function getEncoding()
    {
        return $this->encoding;
    }
    
    /**
     * Set the encoding parameter. By default 'ISO8859-1' is used.
     * 
     * @param string $encoding SMS encoding
     */
    public function setEncoding($encoding)
    {
        $this->encoding = $encoding;
    }
  
}

