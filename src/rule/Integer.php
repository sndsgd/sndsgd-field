<?php

namespace sndsgd\field\rule;

use \sndsgd\field\Collection;
use \sndsgd\field\ValidationError;
use \sndsgd\util\Str;


/**
 * Ensure a value is an integer
 */
class Integer extends \sndsgd\field\Rule
{
   /**
    * {@inheritdoc}
    */
   protected $message = 'must be an integer';

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
      return (
         is_bool($value) || 
         $value === null ||
         ($newVal = filter_var($value, FILTER_VALIDATE_INT)) === false
      ) 
         ? new ValidationError($this->message, $value, $name, $index)
         : $newVal;
   }
}

