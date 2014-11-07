<?php

namespace sndsgd\field\rule;

use \sndsgd\field\Collection;
use \sndsgd\field\ValidationError;


/**
 * Ensure a value is not boolean
 *
 * Note: used to determine if a command line field has a value
 */
class NotBoolean extends \sndsgd\field\Rule
{
   /**
    * {@inheritdoc}
    */
   protected $message = 'missing required value';

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
      if (!is_bool($value)) {
         return $value;
      }
      return new ValidationError($this->message, $value, $name, $index);
   }
}

