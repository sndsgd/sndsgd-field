<?php

namespace sndsgd\field\rule;

use \sndsgd\field\Collection;
use \sndsgd\field\ValidationError;


/**
 * Ensure a value is a number
 */
class Number extends \sndsgd\field\Rule
{
   /**
    * {@inheritdoc}
    */
   protected $message = 'must be a number';

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
      if ($value === 0 || is_numeric($value)) {
         return true;
      }
      return new ValidationError($this->message, $value, $name, $index);
   }
}

