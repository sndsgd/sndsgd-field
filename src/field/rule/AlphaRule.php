<?php

namespace sndsgd\field\rule;

use \sndsgd\Field;
use \sndsgd\field\Collection;
use \sndsgd\field\Error;


/**
 * Ensure a value only contains alphabetical characters
 */
class AlphaRule extends \sndsgd\field\Rule
{
   /**
    * {@inheritdoc}
    */
   protected $message = 'contains non alphabetical characters';

   /**
    * {@inheritdoc}
    */
   public function validate()
   {
      return ctype_alpha($this->value);
   }
}

