<?php

namespace sndsgd\field\rule;

use \sndsgd\field\Collection;
use \sndsgd\field\ValidationError;
use \sndsgd\Str;


/**
 * Ensure a value is a float
 */
class Float extends \sndsgd\field\Rule
{
   /**
    * {@inheritdoc}
    */
   protected $message = 'must be a float';

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
      if (is_string($value)) {
         if (preg_match('~^([0-9\\.-]+)$~', $value)) {
            $newVal = Str::toNumber($value);
            return floatval($newVal);
         }
      }
      else if (is_int($value)) {
         return floatval($value);
      }
      else if (!is_bool($value) && filter_var($value, FILTER_VALIDATE_FLOAT)) {
         return floatval($value);
      }
      return new ValidationError($this->message, $value, $name, $index);
   }
}

