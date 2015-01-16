<?php

namespace sndsgd\field\rule;

use \sndsgd\Field;
use \sndsgd\field\Collection;
use \sndsgd\field\Error;


/**
 * Ensure a value is a valid email address
 */
class EmailRule extends \sndsgd\field\Rule
{
   /**
    * {@inheritdoc}
    */
   protected $message = 'invalid email address';

   /**
    * {@inheritdoc}
    */
   public function validate()
   {
      $result = filter_var($this->value, FILTER_VALIDATE_EMAIL);
      if ($result !== false) {
         $this->value = $result;
         return true;
      }
      return false;
   }
}

