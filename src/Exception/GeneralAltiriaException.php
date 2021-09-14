<?php

namespace AltiriaSmsPhpClient\Exception;

/**
 * General exception.
 */
class GeneralAltiriaException extends \Exception 
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
        parent::__construct();
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
        return 'GeneralAltiriaException: message='. $this->message.', status='.$this->status;
    }
}



