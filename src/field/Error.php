<?php

namespace sndsgd\field;

use \Exception;
use \sndsgd\Field;


/**
 * A representation of a value that failed to validate
 */
class Error
{
    /**
     * A human readable error message
     * 
     * @var string
     */
    protected $message;

    /** 
     * The name of the field in which the error occured
     * 
     * @var string
     */
    protected $name;

    /** 
     * The index of the value in the field that caused the error
     * 
     * @var number
     */
    protected $index;

    /** 
     * The value of the field in which the error occured (limited to 99 chars)
     * 
     * @var interger|float|string
     */
    protected $value;

    /**
     * Create a validation error
     *
     * @param string $message A human readable error message
     */
    public function __construct($message)
    {
        $this->message = $message;    
    }

    /**
     * Get a message that describes the error
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set the name of the field that failed to validate
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get the name of the field that failed to validate
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the index of the value in the field that failed to validate
     *
     * @param integer $index
     */
    public function setIndex($index)
    {
        $this->index = $index;
    }

    /**
     * Get the index of the value in the field that failed to validate
     *
     * @return integer
     */
    public function getIndex()
    {
        return $this->index;
    }

    /**
     * Set the value in the field that failed to validate
     * 
     * @param mixed $value
     */
    public function setValue($value)
    {
        if (is_string($value) && strlen($value) > 100) {
            $value = substr($value, 0, 96).'...'; 
        }
        $this->value = $value;
    }

    /**
     * Get the value in the field that failed to validate
     *
     * @return string|integer|float|boolean
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Export the error as an array
     * 
     * @return array<string,mixed>
     */
    public function export()
    {
        return [
            "name" => $this->name,
            "index" => $this->index,
            "message" => $this->message,
            "value" => $this->value
        ];
    }
}
