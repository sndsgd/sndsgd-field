<?php

namespace sndsgd\field;

use \InvalidArgumentException;
use \sndsgd\field\rule\Float as FloatRule;


/**
 * A field for storing float values
 */
class Float extends \sndsgd\Field
{
   /**
    * {@inheritdoc}
    */
   public function __construct($name)
   {
      parent::__construct($name);
      $this->addRules(new FloatRule);
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

