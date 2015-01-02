<?php

namespace sndsgd\field\rule;

use \sndsgd\field\Collection;
use \sndsgd\field\ValidationError;

/**
 * Ensure a value only contains alphabetical characters
 */
class Alpha extends \sndsgd\field\Rule
{
   /**
    * {@inheritdoc}
    */
   protected $message = 'contains non alphabetical characters';

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
      if (ctype_alpha($value)) {
         return $value;
      }
      return new ValidationError($this->message, $value, $name, $index);
   }
}

