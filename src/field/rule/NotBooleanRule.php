<?php

namespace sndsgd\field\rule;

use \sndsgd\Field;
use \sndsgd\field\Collection;
use \sndsgd\field\Error;


/**
 * Ensure a value is not boolean
 *
 * Note: used to determine if a command line field has a value
 */
class NotBooleanRule extends \sndsgd\field\Rule
{
   /**
    * {@inheritdoc}
    */
   protected $message = 'missing required value';

   /**
    * {@inheritdoc}
    */
   public function validate()
   {
      return is_bool($this->value) === false;
   }
}

