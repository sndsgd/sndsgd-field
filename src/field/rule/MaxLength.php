<?php

namespace sndsgd\field\rule;

use \InvalidArgumentException;
use \sndsgd\field\Collection;
use \sndsgd\field\ValidationError;


/**
 * Ensure a value doesn't contain too many characters
 */
class MaxLength extends \sndsgd\field\Rule
{
   /**
    * {@inheritdoc}
    */
   protected $message = 'must be no more than {{len}} {{char}}';

   /**
    * The maximum number of characters required
    *
    * @var integer
    */
   protected $max = 1;

   /**
    * @param string $value The regex to use in validation
    */
   public function __construct($value = null)
   {
      if ($value === null || !is_int($value) || $value < 1) {
         throw new InvalidArgumentException(
            "invalid value provided for 'value'; ".
            "expecting a integer that is greater than 0"
         );
      }
      $this->max = $value;
   }

   /**
    * Create a message that specifies the number of characters required
    *
    * @return string
    */
   private function getMessage()
   {
      $ret = $this->message;
      $search = [
         '{{len}}' => $this->max,
         '{{char}}' => ($this->max === 1) ? 'character' : 'characters'
      ];
      
      foreach ($search as $find => $replace) {
         $ret = str_replace($find, $replace, $ret);
      }
      return $ret;
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
      return (strlen($value) > $this->max)
         ? new ValidationError($this->getMessage(), $value, $name, $index)
         : $value;
   }
}

