<?php

namespace sndsgd\field\rule;

use \sndsgd\field\Collection;
use \sndsgd\field\ValidationError;


/**
 * Ensure a value is a valid email address
 */
class Email extends \sndsgd\field\Rule
{
   /**
    * {@inheritdoc}
    */
   protected $message = 'invalid email address';

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
      if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
         return $value;
      }
      return new ValidationError($this->message, $value, $name, $index);
   }
}

