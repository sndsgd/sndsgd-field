<?php

namespace sndsgd\field\rule;

use \sndsgd\Field;
use \sndsgd\field\Collection;
use \sndsgd\field\Error;
use \sndsgd\Classname as Cname;


/**
 * Ensure a classname string is valid
 */
class ClassnameRule extends \sndsgd\field\Rule
{
   const REGEX = '/^(\\\\)?([a-z][a-z0-9_]+)((?:\\\\[a-z][a-z0-9_]+)+)?$/i';

   /**
    * {@inheritdoc}
    */
   protected $message = 'invalid classname';

   /**
    * {@inheritdoc}
    */
   public function validate()
   {
      $classname = Cname::toString($this->value);
      if (preg_match(self::REGEX, $classname) === 1) {
         $this->value = $classname;
         return true;
      }
      return false;
   }
}

