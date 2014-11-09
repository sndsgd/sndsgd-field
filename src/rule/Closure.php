<?php

namespace sndsgd\field\rule;

use \InvalidArgumentException;
use \sndsgd\field\Collection;
use \sndsgd\field\ValidationError;


/**
 * Ensure a value doesn't contain too many characters
 */
class Closure extends \sndsgd\field\Rule
{
   /**
    * {@inheritdoc}
    */
   protected $message = '';

   /**
    * The handler function
    *
    * @var callable
    */
   protected $fn;

   /**
    * @param string $value The regex to use in validation
    */
   public function __construct($value = null)
   {
      if ($value === null || !is_callable($value)) {
         throw new InvalidArgumentException(
            "invalid value provided for 'value'; ".
            "expecting a closure"
         );
      }
      $this->fn = $value;
   }

   /**
    * Ensure that multiple Closure rules can be added to a field
    *
    * @return string
    */
   public function getClass()
   {
      return get_called_class().'('.microtime(true).')';
   }

   /**
    * {@inheritdoc}
    */
   public function validate(
      $value, 
      $name = null, 
      $index = null, 
      Collection $collection = null
   )
   {
      $result = call_user_func($this->fn, $value, null, $name, $index, $collection);
      if (
         $result instanceof ValidationError &&
         $this->message !== ''
      ) {
         $result->setMessage($this->message);
      }
      return $result;
   }
}

