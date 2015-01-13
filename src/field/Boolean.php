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
    * The default value for boolean fields should always be false
    *
    * @var boolean
    */
   protected $defaultValue = false;

   /**
    * {@inheritdoc}
    */
   public function __construct($name)
   {
      parent::__construct($name);
      $this->addRules(
         new MaxValueCountRule(1),
         new BooleanRule()
      );
   }

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

