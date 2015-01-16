<?php

namespace sndsgd\field;

use \DateTime;
use \InvalidArgumentException;
use \sndsgd\field\rule\DateStringRule;


/**
 * A field for storing integer values
 */
class DateField extends \sndsgd\Field
{
   /**
    * {@inheritdoc}
    */
   public function __construct($name)
   {
      parent::__construct($name);
      $this->addRule(new DateStringRule);
   }

   /**
    * {@inheritdoc}
    * @param DateTime $value
    */
   public function setDefault($value)
   {
      if (($value instanceof DateTime) === false) {
         throw new InvalidArgumentException(
            "invalid value provided for 'value'; ".
            "expecting an instance of DateTime"
         );
      }
      $this->defaultValue = $value;
      return $this;
   }
}

