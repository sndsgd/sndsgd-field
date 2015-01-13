<?php

namespace sndsgd\field;

use \InvalidArgumentException;
use \sndsgd\field\rule\Integer as IntegerRule;


/**
 * A field for storing integer values
 */
class Integer extends \sndsgd\Field
{
   /**
    * {@inheritdoc}
    */
   public function __construct($name)
   {
      parent::__construct($name);
      $this->addRules(new IntegerRule);
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

