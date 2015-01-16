<?php

namespace sndsgd\field\rule;

use \sndsgd\Field;
use \sndsgd\field\Collection;
use \sndsgd\field\Error;
use \sndsgd\Str;


/**
 * Ensure a value is boolean
 */
class BooleanRule extends \sndsgd\field\Rule
{
   /**
    * {@inheritdoc}
    */
   protected $message = 'must be a boolean';

   /**
    * {@inheritdoc}
    */
   public function validate()
   {
      if (is_bool($this->value)) {
         return true;
      }
      else if (
         (is_string($this->value) || is_int($this->value)) &&
         ($newValue = Str::toBoolean($this->value)) !== null
      ) {
         $this->value = $newValue;
         return true;
      }
      return false;
   }
}

