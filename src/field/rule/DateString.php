<?php

namespace sndsgd\field\rule;

use \DateTime;
use \sndsgd\field\Collection;
use \sndsgd\field\ValidationError;


/**
 * Ensure a string can be converted to a DateTime object
 */
class DateString extends \sndsgd\field\Rule
{
   /**
    * {@inheritdoc}
    */
   protected $message = 'failed to decipher date';

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
      if (
         !is_string($value) ||
         trim($value) === '' ||
         ($timestamp = strtotime($value)) === false
      ) {
         return new ValidationError($this->message, $value, $name, $index);
      }
      return (new DateTime())->setTimestamp($timestamp);
   }
}

