<?php

namespace sndsgd\field;

use \InvalidArgumentException;
use \sndsgd\field\rule\FloatRule;
use \sndsgd\field\rule\MaxValueRule;
use \sndsgd\field\rule\MinValueRule;


/**
 * A field for storing a longitudinal value
 */
class LongitudeField extends \sndsgd\Field
{
   /**
    * {@inheritdoc}
    */
   public function __construct($name)
   {
      parent::__construct($name);
      $this->addRules([
         new FloatRule,
         new MinValueRule(-180),
         new MaxValueRule(180)
      ]);
   }

   /**
    * {@inheritdoc}
    * @param float $value
    */
   public function setDefault($value)
   {
      if (!is_float($value) || $value < -180 || $value > 180) {
         throw new InvalidArgumentException(
            "invalid value provided for 'value'; ".
            "expecting a float that is between -180 and 180"
         );
      }
      $this->defaultValue = $value;
      return $this;
   }
}

