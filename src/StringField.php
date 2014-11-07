<?php

namespace sndsgd\field;

use \InvalidArgumentException;


/**
 * A field for storing string values
 */
class StringField extends Field
{
   /**
    * Create a string field with sensible default rules
    *
    * @param string $name The name of the field
    * @return sndsgd\field\StringField
    */
   public static function create($name, $default = null)
   {
      return new self($name);
   }

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

