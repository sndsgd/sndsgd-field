<?php

namespace sndsgd\field\rule;

use \sndsgd\field\Collection;
use \sndsgd\field\ValidationError;


/**
 * Ensure a value only contains alphanumeric characters
 */
class AlphaNum extends \sndsgd\field\Rule
{
   /**
    * {@inheritdoc}
    */
   protected $message = 'contains non alphanumeric characters';

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
      return (ctype_alnum($value))
         ? $value
         : new ValidationError($this->message, $value, $name, $index);
   }
}

