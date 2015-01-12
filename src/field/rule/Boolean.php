<?php

namespace sndsgd\field\rule;

use \sndsgd\field\Collection;
use \sndsgd\field\ValidationError;
use \sndsgd\Str;


/**
 * Ensure a value is boolean
 */
class Boolean extends \sndsgd\field\Rule
{
   /**
    * {@inheritdoc}
    */
   protected $message = 'must be a boolean';

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
      if (is_bool($value)) {
         return $value;
      }
      else if (
         (is_string($value) || is_int($value)) &&
         ($newVal = Str::toBoolean($value)) !== null
      ) {
         return $newVal;
      }
      return new ValidationError($this->message, $value, $name, $index);
   }
}

