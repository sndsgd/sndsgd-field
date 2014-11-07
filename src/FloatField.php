<?php

namespace sndsgd\field;

use \InvalidArgumentException;
use \sndsgd\field\rule\Float as FloatRule;


/**
 * A field for storing float values
 */
class FloatField extends Field
{
   /**
    * Create a float field with sensible default rules
    *
    * @param string $name The name of the field
    * @return sndsgd\field\FloatField
    */
   public static function create($name)
   {
      $field = new self($name);
      $field->addRules(new FloatRule);
      return $field;
   }

   /**
    * {@inheritdoc}
    * @param float $value
    */
   public function setDefault($value)
   {
      if (!is_float($value)) {
         throw new InvalidArgumentException(
            "invalid value provided for 'value'; expecting a float"
         );
      }
      $this->defaultValue = $value;
      return $this;
   }
}

