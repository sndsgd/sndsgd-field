<?php

namespace sndsgd\field;

use \InvalidArgumentException;
use \sndsgd\field\rule\Integer as IntegerRule;


/**
 * A field for storing integer values
 */
class IntegerField extends \sndsgd\Field
{
   /**
    * Create an integer field with sensible default rules
    *
    * @param string $name The name of the field
    * @return sndsgd\field\IntegerField
    */
   public static function create($name)
   {
      $field = new self($name);
      $field->addRules(new IntegerRule);
      return $field;
   }

   /**
    * {@inheritdoc}
    * @param integer $value
    */
   public function setDefault($value)
   {
      if (!is_int($value)) {
         throw new InvalidArgumentException(
            "invalid value provided for 'value'; expecting an integer"
         );
      }
      $this->defaultValue = $value;
      return $this;
   }
}

