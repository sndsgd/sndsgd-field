<?php

namespace sndsgd\field\rule;

use \sndsgd\Field;
use \sndsgd\field\Collection;


/**
 * Ensure a value is an empty directory
 *
 * Note: if you use this rule, you should use sndsgd\field\rule\PathTestRule 
 * first to ensure the value is actually a directory
 */
class EmptyDirectoryRule extends \sndsgd\field\Rule
{
   /**
    * {@inheritdoc}
    */
   protected $message = "'{{path}}' must be an empty directory";

   /**
    * {@inheritdoc}
    */
   public function getMessage()
   {
      return str_replace('{{path}}', $this->value, $this->message);
   }

   /**
    * {@inheritdoc}
    */
   public function validate()
   {
      return (count(scandir($this->value)) === 2);
   }
}

