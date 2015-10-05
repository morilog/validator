<?php namespace Laratalks\Validator;

use Illuminate\Contracts\Foundation\Application;
use Laratalks\Validator\Exceptions;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Factory;
use Laratalks\Validator\Exceptions\ValidationException;

abstract class AbstractValidator
{

    const EXCEPTION_KEY = 'messages.validation_failed';

    /**
     * Http status code
     *
     * @var int
     */
    protected static $statusCode = 422;

    /**
     * Rules to check for
     *
     * @var array
     */
    protected $rules = [];

    /**
     * Custom Attributes
     *
     * @var array
     */
    protected $customAttributes = [];

    /**
     * Messages to append
     *
     * @var array
     */
    protected $messages = [];

    /**
     * @var Factory
     */
    protected $validatorFactory;

    /**
     * @var Validator
     */
    protected $validated;

    /**
     * Sending exception status
     *
     * @var bool
     */
    protected $exceptionStatus = true;

    /**
     * Scenario
     * @var string
     */
    protected $scenario;

    /**
     * @param Factory $validatorFactory
     * @param Application $application
     */
    public function __construct(Factory $validatorFactory, Application $application)
    {
        $this->validatorFactory = $validatorFactory;
        $this->application = $application;
    }

    /**
     * Validate the data given with some optional rules and messages
     *
     * @param array $data
     * @param null $rules
     * @param array $messages
     * @param array $customAttributes
     * @return Validator|\Illuminate\Validation\Validator
     * @throws ValidationException
     */
    public function validate(array $data, $rules = null, array $messages = [], array $customAttributes = [])
    {
        $rules = $rules ?: $this->rules;

        if ($this->getScenario() !== null) {
            $scenarioRules = camel_case($this->getScenario() . 'Rules');

            if ($this->{$scenarioRules} !== null) {
                $rules = $this->{$scenarioRules};
            }
        }

        $messages = empty($messages) ? $this->messages : $messages;

        $customAttributes = empty($customAttributes) ? $this->customAttributes : $customAttributes;

        $this->validated = $this->validatorFactory->make($data, $rules, $messages, $customAttributes);

        if ($this->exceptionStatus) {

            if ($this->validated->fails()) {


                $e = new ValidationException($this->getFailMessage(), static::$statusCode, static::EXCEPTION_KEY);

                $e->setErrors($this->validated->messages());

                throw $e;


            }

        }

        return $this->validated;
    }

    /**
     * Does validation fails with given data
     *
     * @return bool
     * @throws \Exception
     */
    public function fails()
    {
        if (is_null($this->validated)) {
            throw new \Exception("No data has been validated yet");
        }

        return $this->validated->fails();
    }

    /**
     * Does validation passes by gieven data
     *
     * @return bool
     * @throws \Exception
     */
    public function passes()
    {
        return !$this->fails();
    }

    /**
     * Validate for update
     *
     * @param array $data
     * @param null $rules
     * @param array $messages
     * @param array $customAttributes
     * @throws ValidationException
     */
    public function updateValidate(array $data, $rules = null, $messages = [], array $customAttributes = [])
    {
        $rules = $rules ?: $this->updateRules;

        $messages = empty($messages) ? $this->messages : $messages;

        $customAttributes = empty($customAttributes) ? $this->customAttributes : $customAttributes;

        $this->validate($data, $rules, $messages, $customAttributes);
    }

    /**
     * Set exception status
     *
     * @param $status
     * @return $this
     */
    public function exceptionStatus($status)
    {
        $this->exceptionStatus = (bool)$status;

        return $this;
    }

    /**
     * Get failing message
     *
     * @return string
     */
    protected function getFailMessage()
    {
        return $this->application('translator')->trans(static::EXCEPTION_KEY);
    }

    public function setScenario($scenario)
    {
        $this->scenario = $scenario;

        return $this;
    }

    protected function getScenario()
    {
        return $this->scenario;
    }



}