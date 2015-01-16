<?php

namespace sndsgd\field\rule;

use \sndsgd\Field;
use \sndsgd\field\Collection;
use \sndsgd\field\Error;


/**
 * Ensure a value isn't empty or null
 */
class RequiredRule extends \sndsgd\field\Rule
{
   /**
    * {@inheritdoc}
    */
   protected $message = 'this is a required field';

   /**
    * {@inheritdoc}
    */
   public function validate()
   {
      return ($this->value !== null && $this->value !== '');
   }
}

