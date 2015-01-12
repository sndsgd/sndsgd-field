<?php

namespace sndsgd\field;

use \InvalidArgumentException;
use \sndsgd\field\rule\Boolean as BooleanRule;
use \sndsgd\field\rule\MaxValueCount as MaxValueCountRule;


/**
 * A field for storing boolean values
 */
class Boolean extends \sndsgd\Field
{
   /**
    * Create a boolean field with sensible default rules
    *
    * @param string $name The name of the field
    * @return sndsgd\field\Boolean
    */
   public static function create($name)
   {
      $field = new self($name);
      $field->addRules(
         new MaxValueCountRule(1),
         new BooleanRule()
      );
      return $field;
   }

   /**
    * The default value for boolean fields should always be false
    *
    * @var boolean
    */
   protected $defaultValue = false;

   /**
    * {@inheritdoc}
    * @param boolean $value
    */
   public function setDefault($value)
   {
      if (!is_bool($value)) {
         throw new InvalidArgumentException(
            "invalid value provided for 'value'; expecting a boolean"
         );
      }
      $this->defaultValue = $value;
      return $this;
   }
}

