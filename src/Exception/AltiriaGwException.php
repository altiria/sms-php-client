<?php

namespace AltiriaSmsPhpClient\Exception;

/**
 * This exception is thrown if the HTTP status is 200 but the response body contains an Altiria error code.
 */
class AltiriaGwException extends GeneralAltiriaException 
{
    protected $message;
    protected $status;

    /**
     * Constructor.
     * 
     * @param string $message error message
     * @param string $status  status code
     */
    public function __construct($message, $status)
    {
        $this->message = $message;
        $this->status = $status;
        parent::__construct($message, $status);
    }

    /**
     * Get the status value
     * 
     * @return $string status status code value
     */
    public function getStatus()
    {
        return $this->status;
    }
    
    /**
     * To string function
     * 
     * @return string AltiriaGwException 
     */
    public function __toString() 
    {
        return 'AltiriaGwException: message='. $this->message.', status='.$this->status;
    }
}



