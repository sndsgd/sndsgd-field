<?php

namespace sndsgd\field\rule;

use \sndsgd\field\Collection;
use \sndsgd\field\ValidationError;


/**
 * Ensure a value isn't empty or null
 */
class Required extends \sndsgd\field\Rule
{
   /**
    * {@inheritdoc}
    */
   protected $message = 'this is a required field';

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
      return ($value !== null && $value !== '')
         ? $value
         : new ValidationError($this->message, $value, $name, $index);
   }
}

