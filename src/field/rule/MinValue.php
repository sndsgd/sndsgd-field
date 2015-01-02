<?php

namespace sndsgd\field\rule;

use \InvalidArgumentException;
use \sndsgd\field\Collection;
use \sndsgd\field\ValidationError;


/**
 * Ensure a value is at least a certain value
 */
class MinValue extends \sndsgd\field\Rule
{
   /**
    * {@inheritdoc}
    */
   protected $message = 'must be no less than {{min}}';

   /**
    * The minimum value
    *
    * @var integer
    */
   protected $min = 1;

   /**
    * @param integer $value The min value for comparison
    */
   public function __construct($value = null)
   {
      if ($value === null || (!is_int($value) && !is_float($value))) {
         throw new InvalidArgumentException(
            "invalid value provided for 'value'; ".
            "expecting an integer or a float"
         );
      }
      $this->min = $value;
   }

   /**
    * Create a message that specifies the min value
    *
    * @return string
    */
   private function getMessage()
   {
      return str_replace('{{min}}', $this->min, $this->message);
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
      return ($value < $this->min)
         ? new ValidationError($this->getMessage(), $value, $name, $index)
         : $value;
   }
}

