<?php

namespace sndsgd\field\rule;

use \InvalidArgumentException;
use \sndsgd\field\Collection;
use \sndsgd\field\ValidationError;


/**
 * Ensure a field doesn't have too many values
 */
class MinValueCount extends \sndsgd\field\Rule
{
   /**
    * {@inheritdoc}
    */
   protected $message = 'provided too few times';

   /**
    * The minimum number of values that must be present in a field
    *
    * @var integer
    */
   protected $min = 1;

   /**
    * @param string $value the min number of values in a field
    */
   public function __construct($value = null)
   {
      if (!is_int($value) || $value < 1) {
         throw new InvalidArgumentException(
            "invalid value provided for 'value'; ".
            "expecting an integer that is greater than or equal to 1"
         );
      }
      $this->min = $value;
   }

   /**
    * {@inheritdoc}
    */
   public function validate(
      $value, 
      $name = null, 
      $index = null, 
      Collection $collection = null
   )
   {
      $field = $collection->getField($name);
      return ($field->getValueCount() < $this->min)
         ? new ValidationError($this->message, $value, $name, $index)
         : $value;
   }
}

