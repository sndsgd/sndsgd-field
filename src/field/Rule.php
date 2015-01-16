<?php

namespace sndsgd\field;

use \InvalidArgumentException;
use \sndsgd\Field;


/**
 * A field validation and/or formatting rule
 */
abstract class Rule
{
   /**
    * The classname of the rule that validates a required value
    *
    * This is used by sndsgd\Field::validate to determine whether or not
    * an empty value should be validated
    * @var string
    */
   const REQUIRED = 'sndsgd\\field\\rule\\RequiredRule';

   /**
    * The error message when validation fails
    * 
    * @var string
    */
   protected $message = 'invalid value';

   /**
    * The value to validate
    * 
    * @var mixed
    */
   protected $value;

   /**
    * If the value is part of a field, its index within the field
    * 
    * @var integer
    */
   protected $valueIndex;

   /**
    * The value's parent field instance
    * 
    * @var sndsgd\Field|null
    */
   protected $field;

   /**
    * The parent collection of the value's parent field
    * 
    * @var sndsgd\field\Collection|null
    */
   protected $collection;

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
    * Validate a value
    * 
    * @param string|integer|float|boolean $value
    * @return boolean
    * @return boolean:true Validation was successfull
    * @return boolean:false Validation failed
    */
   abstract public function validate();

   /**
    * Get the rule classname
    *
    * Used by sndsgd\Field to determine if the rule already exists
    * @return string
    */
   public function getClass()
   {
      return get_called_class();
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
    * Get the error message
    * 
    * @return string
    */
   protected function getMessage()
   {
      return $this->message;
   }

   /**
    * Set the value to validate
    * 
    * @param mixed $value
    */
   public function setValue($value)
   {
      $this->value = $value;
   }

   /**
    * Get the value
    * 
    * @return mixed
    */
   public function getValue()
   {
      return $this->value;
   }

   /**
    * Set the value's parent field for use in validation
    * 
    * @param sndsgd\Field $field
    * @param integer $index The index of the value in the field
    */
   public function setField(Field $field, $index = 0)
   {
      $this->field = $field;
      $this->valueIndex = $index;
   }

   /**
    * Set a field collection for use in validation
    * 
    * @param sndsgd\field\Collection $collection
    */
   public function setCollection(Collection $collection)
   {
      $this->collection = $collection;
   }

   /**
    * Get an error instance
    * 
    * @return sndsgd\field\Error
    */
   public function getError()
   {
      $error = new Error($this->getMessage());
      $error->setValue($this->value);
      if ($this->field !== null) {
         $error->setName($this->field->getName());
      }
      if ($this->valueIndex !== null) {
         $error->setIndex($this->valueIndex);
      }
      return $error;
   }
}

