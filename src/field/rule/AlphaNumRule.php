<?php

namespace sndsgd\field\rule;

use \sndsgd\Field;
use \sndsgd\field\Collection;
use \sndsgd\field\Error;


/**
 * Ensure a value only contains alphanumeric characters
 */
class AlphaNumRule extends \sndsgd\field\Rule
{
   /**
    * {@inheritdoc}
    */
   protected $message = 'contains non alphanumeric characters';

   /**
    * {@inheritdoc}
    */
   public function validate()
   {
      return ctype_alnum($this->value);
   }
}

