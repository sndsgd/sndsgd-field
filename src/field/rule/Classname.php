<?php

namespace sndsgd\field\rule;

use \sndsgd\field\Collection;
use \sndsgd\field\ValidationError;
use \sndsgd\Classname as Cname;


/**
 * Ensure a classname string is valid
 */
class Classname extends \sndsgd\field\Rule
{
   const REGEX = '/^(\\\\)?([a-z][a-z0-9_]+)((?:\\\\[a-z][a-z0-9_]+)+)?$/i';

   /**
    * {@inheritdoc}
    */
   protected $message = 'invalid classname';

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
      $class = Cname::toString($value);
      if (preg_match(self::REGEX, $class) === 1) {
         return $class;
      }
      return new ValidationError($this->message, $value, $name, $index);
   }
}

