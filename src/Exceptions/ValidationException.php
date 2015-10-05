<?php 
namespace Laratalks\Validator\Exceptions;

class ValidationException extends \Exception implements ValidationExceptionInterface {

    /**
     * @var |string
     */
    protected $exceptionKey = 'exception';

    /**
     * @param string $message
     * @param int $code
     */
    public function __construct($message, $code, $exceptionKey)
    {
        $this->setExceptionKey($exceptionKey);

        parent::__construct($message, $code);
    }

    /**
     * @var mixed
     */
    protected $validationErrors;

    /**
     * Set validation errors
     *
     * @param $errors
     * @return $this
     */
    public function setErrors($errors)
    {
        $this->validationErrors = $errors; return $this;
    }

    /**
     * Get validation errors
     *
     * @return mixed
     */
    public function getErrors()
    {
        return $this->validationErrors;
    }

    /**
     * Set exception key
     *
     * @param $key
     */
    public function setExceptionKey($key)
    {
        $this->exceptionKey = $key;
    }

    /**
     * Get exception key
     *
     * @return \Exception
     */
    public function getExceptionKey()
    {
        return $this->exceptionKey;
    }
}