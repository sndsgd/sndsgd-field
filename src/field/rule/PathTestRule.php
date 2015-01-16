<?php

namespace sndsgd\field\rule;

use \InvalidArgumentException;
use \sndsgd\Field;
use \sndsgd\field\Collection;
use \sndsgd\field\Error;
use \sndsgd\Path;


/**
 * Ensure a path validates using sndsgd\Path::test
 */
class PathTestRule extends \sndsgd\field\Rule
{
   /**
    * The bitmask to pass to sndsgd\Path::test
    *
    * @var integer
    */
   protected $bitmask = 1;

   /**
    * @param string $bitmask The bitmask to pass to sndsgd\Path::test
    */
   public function __construct($bitmask = null)
   {
      if ($bitmask === null || !is_int($bitmask)) {
         throw new InvalidArgumentException(
            "invalid value provided for 'bitmask'; ".
            "expecting an integer"
         );
      }
      $this->bitmask = $bitmask;
   }

   /**
    * {@inheritdoc}
    * 
    * @todo the call to setMessage will override custom message if one is set
    */
   public function validate()
   {
      $path = Path::normalize($this->value);
      $test = Path::test($path, $this->bitmask);
      if ($test === true) {
         $this->value = $path;
         return true;
      }
      else {
         $this->setMessage($test);
         return false;
      }
   }
}

