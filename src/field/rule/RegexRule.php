<?php

namespace sndsgd\field\rule;

use \InvalidArgumentException;
use \sndsgd\Field;
use \sndsgd\field\Collection;
use \sndsgd\field\Error;


/**
 * Ensure a value matches a regex pattern
 */
class RegexRule extends \sndsgd\field\Rule
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
   public function validate()
   {
      return preg_match($this->regex, $this->value) === 1;
   }
}

