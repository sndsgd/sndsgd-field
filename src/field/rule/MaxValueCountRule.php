<?php

namespace sndsgd\field\rule;

use \Exception;
use \InvalidArgumentException;
use \sndsgd\Field;
use \sndsgd\field\Collection;
use \sndsgd\field\Error;
use \sndsgd\Str;


/**
 * Ensure a field has enough values
 */
class MaxValueCountRule extends \sndsgd\field\Rule
{

   /**
    * {@inheritdoc}
    */
   protected $message = 'expecting no more than {{len}} {{what}}';

   /**
    * The max number of values that must be present in a field
    *
    * @var integer
    */
   protected $max = 1;

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
      $this->max = $value;
   }

   /**
    * {@inheritdoc}
    */
   public function getMessage()
   {
      return Str::replace($this->message, [
         '{{len}}' => $this->max,
         '{{what}}' => ($this->max === 1) ? 'value' : 'values'
      ]);
   }

   /**
    * {@inheritdoc}
    */
   public function validate()
   {
      if ($this->field === null) {
         throw new Exception("no field defined");
      }
      return (count($this->field) <= $this->max);
   }
}

