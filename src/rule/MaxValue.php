<?php

namespace sndsgd\field\rule;

use \InvalidArgumentException;
use \sndsgd\field\Collection;
use \sndsgd\field\ValidationError;


/**
 * Ensure a value is at no more than a certain value
 */
class MaxValue extends \sndsgd\field\Rule
{
   /**
    * {@inheritdoc}
    */
   protected $message = 'must be no more than {{len}}';

   /**
    * The maximum value
    *
    * @var integer
    */
   protected $max = 1;

   /**
    * @param integer $value The max value for comparison
    */
   public function __construct($value = null)
   {
      if ($value === null || (!is_int($value) && !is_float($value))) {
         throw new InvalidArgumentException(
            "invalid value provided for 'value'; ".
            "expecting an integer or a float"
         );
      }
      $this->max = $value;
   }

   /**
    * Create a message that specifies the max value
    *
    * @return string
    */
   private function getMessage()
   {
      return str_replace('{{len}}', $this->max, $this->message);
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
      return ($value > $this->max)
         ? new ValidationError($this->getMessage(), $value, $name, $index)
         : $value;
   }
}

