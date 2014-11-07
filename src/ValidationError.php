<?php

namespace sndsgd\field;

use \Exception;


/**
 * A representation of a value that failed to validate
 */
class ValidationError
{
   /** 
    * The name of the field in which the error occured
    * 
    * @var string
    */
   private $name;

   /** 
    * The index of the value in the field that caused the error
    * 
    * @var number
    */
   private $index;

   /** 
    * The value of the field in which the error occured (limited to 99 chars)
    * 
    * @var interger|float|string
    */
   private $value;

   /**
    * A human readable error message
    * 
    * @var string
    */
   private $message;

   /**
    * References to other fields that caused, or are involved with the error
    * 
    * Example:
    * <code>
    * [
    *    [string $name, integer $index],
    *    [string $name, integer $index],
    *    ...
    * ]
    * </code>
    * @var array
    */
   private $references = null;


   /**
    * Create a validation error
    *
    * @param string $message A human readable error message
    * @param mixed $value The value that caused the error
    * @param string|null $name The name of the field with the invalid value
    * @param integer|null $index The index of the value in the field ...
    */
   public function __construct($message, $value, $name = null, $index = null)
   {
      $this->setMessage($message);
      if (is_string($value) && strlen($value) > 100) {
         $value = substr($value, 0, 96).'...'; 
      }

      $this->value = $value;
      $this->name = $name;
      $this->index = $index;
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
    * Get the index of the value in the field that failed to validate
    *
    * @return integer
    */
   public function getIndex()
   {
      return $this->index;
   }

   /**
    * Get the value that was determined to be invalid
    *
    * @return string|integer|float|boolean
    */
   public function getValue()
   {
      return $this->value;
   }

   /**
    * set a message that describes the error
    * 
    * @param string $message
    */
   public function setMessage($message)
   {
      $this->message = $message;
   }

   /**
    * Get the message that describes the validation error
    *
    * @return string
    */
   public function getMessage()
   {
      return $this->message;
   }

   /**
    * add a reference to another field that caused the error
    * 
    * @param string $name The name of the field to reference
    * @param integer $index The index of the field to reference
    * @return sndsgd\field\ValidationError
    */
   public function addReference($name, $index = 0)
   {
      if ($this->references === null) {
         $this->references = [];
      }
      $this->references[] = [ $name, $index ];
      return $this;
   }

   /**
    * Get information regarding the fields that were related to the error
    * 
    * @return array|null
    */
   public function getReferences()
   {
      return $this->references;
   }
}

