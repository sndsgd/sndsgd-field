<?php

namespace sndsgd\field;

use \InvalidArgumentException;
use \sndsgd\field\rule\NotBoolean as NotBooleanRule;


/**
 * A field for storing string values
 */
class String extends \sndsgd\Field
{
   /**
    * {@inheritdoc}
    * @param string $value
    */
   public function setDefault($value)
   {
      if (!is_string($value)) {
         throw new InvalidArgumentException(
            "invalid value provided for 'value'; expecting a string"
         );
      }
      $this->defaultValue = $value;
      return $this;
   }
}

