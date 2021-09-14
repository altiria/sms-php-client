<?php

namespace AltiriaSmsPhpClient\Exception;

/**
 * This exception is thrown if the HTTP status is not equals to 200.
 */
class JsonException extends GeneralAltiriaException 
{
    protected $message;

    /**
     * Constructor.
     * 
     * @param string $message error message
     */
    public function __construct($message)
    {
        $this->message = $message;
        parent::__construct($message, null);
    }
    
    /**
     * To string function
     * 
     * @return string ConnectionException 
     */
    public function __toString() 
    {
        return 'JsonException: message='. $this->message;
    }
}

