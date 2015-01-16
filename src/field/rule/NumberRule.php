<?php

namespace sndsgd\field\rule;

use \sndsgd\Field;
use \sndsgd\field\Collection;
use \sndsgd\field\Error;


/**
 * Ensure a value is a number
 */
class NumberRule extends \sndsgd\field\Rule
{
   /**
    * {@inheritdoc}
    */
   protected $message = 'must be a number';

   /**
    * {@inheritdoc}
    */
   public function validate()
   {
      if ($this->value === 0 || is_numeric($this->value)) {
         $this->value = floatval($this->value);
         return true;
      }
      return false;
   }
}

