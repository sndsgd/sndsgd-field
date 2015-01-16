<?php

namespace sndsgd\field\rule;

use \sndsgd\Field;
use \sndsgd\field\Collection;
use \sndsgd\field\Error;
use \sndsgd\Str;


/**
 * Ensure a value is a float
 */
class FloatRule extends \sndsgd\field\Rule
{
   /**
    * {@inheritdoc}
    */
   protected $message = 'must be a float';

   /**
    * {@inheritdoc}
    */
   public function validate()
   {
      if (is_string($this->value)) {
         if (preg_match('~^([0-9\\.-]+)$~', $this->value)) {
            $this->value = floatval(Str::toNumber($this->value));
            return true;
         }
      }
      else if (
         is_bool($this->value) === false && 
         ($newValue = filter_var($this->value, FILTER_VALIDATE_FLOAT)) !== false
      ) {
         $this->value = $newValue;
         return true;
      }
      return false;
   }
}

