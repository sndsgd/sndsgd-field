<?php

namespace sndsgd\field;

use \InvalidArgumentException;


/**
 * A field validation and/or formatting rule
 */
abstract class Rule
{
   const REQUIRED = 'sndsgd\\field\\rule\\Required';

   /**
    * The error message when validation fails
    * 
    * @var string
    */
   protected $message = 'invalid value';

   /**
    * @param null $value A value for use in the validation method
    */
   public function __construct($value = null)
   {
      if (null !== $value) {
         throw new InvalidArgumentException(
            "invalid value provided for 'value'; expecting no arguments"
         );
      }
   }

   /**
    * Set a custom validation error message
    * 
    * @param string $message
    * @return sndsgd\field\Rule
    * @throws InvalidArgumentException If $message is not a string
    */
   public function setMessage($message)
   {
      if (!is_string($message)) {
         throw new InvalidArgumentException(
            "invalid value provided for 'message'; expecting a string"
         );
      }
      $this->message = $message;
      return $this;
   }

   /**
    * Get the rule classname
    *
    * @return string
    */
   public function getClass()
   {
      return get_called_class();
   }

   /**
    * Validate a value
    * 
    * @param string|integer|float|boolean $value
    * @param string|null $name The name of the field that is being validated
    * @param integer $index
    * @param sndsgd\field\Collection $collection The field's parent collection
    * @return mixed
    * @return string|integer|float|boolean|object If the value is valid
    * @return sndsgd\field\ValidationError If the value is NOT valid
    */
   abstract public function validate(
      $value,
      $name = null,
      $index = null,
      Collection $collection = null
   );
}

