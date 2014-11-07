<?php

namespace sndsgd\field\rule;

use \InvalidArgumentException;
use \sndsgd\field\Collection;
use \sndsgd\field\ValidationError;


/**
 * Ensure a value matches a regex pattern
 */
class Regex extends \sndsgd\field\Rule
{
   /**
    * {@inheritdoc}
    */
   protected $message = 'failed to match pattern';

   /**
    * The regex to match against values
    * 
    * @var string
    */
   protected $regex = null;

   /**
    * @param string $value The regex to use in validation
    */
   public function __construct($value = null)
   {
      if (is_null($value) || @preg_match($value, null) === false) {
         throw new InvalidArgumentException(
            "invalid value provided for 'value'; ".
            "expecting a valid regex as string"
         );
      }
      $this->regex = $value;
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
      return (preg_match($this->regex, $value))
         ? $value
         : new ValidationError($this->message, $value, $name, $index);
   }
}

