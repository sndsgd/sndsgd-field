<?php

namespace sndsgd\field\rule;

use \Exception;
use \InvalidArgumentException;
use \sndsgd\Field;
use \sndsgd\field\Collection;
use \sndsgd\field\Error;
use \sndsgd\Str;


/**
 * Ensure a field doesn't have too many values
 */
class MinValueCountRule extends \sndsgd\field\Rule
{
   const MULTIPLE_VALUE = true;

   /**
    * {@inheritdoc}
    */
   protected $message = 'expecting at least {{min}} {{what}}';

   /**
    * The minimum number of values that must be present in a field
    *
    * @var integer
    */
   protected $min = 1;

   /**
    * {@inheritdoc}
    */
   protected $multiValue = true;

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
   public function getMessage()
   {
      return Str::replace($this->message, [
         '{{len}}' => $this->min,
         '{{what}}' => ($this->min === 1) ? 'value' : 'values'
      ]);
   }

   /**
    * {@inheritdoc}
    */
  public function validate()
   {
      if ($this->field === null) {
         throw new Exception("requires a field");
      }
      return (count($this->field) >= $this->min);
      
   }
}

