<?php
namespace Laratalks\Validator\Exceptions;

interface ValidationExceptionInterface {

    /**
     * Set validation errors
     *
     * @param $errors
     * @return $this
     */
    public function setErrors($errors);

    /**
     * Get validation errors
     *
     * @return mixed
     */
    public function getErrors();
}