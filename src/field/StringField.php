<?php

namespace sndsgd\field;

use \InvalidArgumentException;
use \sndsgd\field\rule\NotBoolean as NotBooleanRule;


/**
 * A field for storing string values
 */
class StringField extends \sndsgd\Field
{
   /**
    * Create a string field with sensible default rules
    *
    * @param string $name The name of the field
    * @return sndsgd\field\StringField
    */
   public static function create($name, $default = null)
   {
      $field = new self($name);
      $field->addRules(new NotBooleanRule());
      return $field;
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

