<?php

namespace sndsgd\field\rule;

use \InvalidArgumentException;
use \sndsgd\field\Collection;
use \sndsgd\field\ValidationError;
use \sndsgd\util\Path;


/**
 * Ensure a path validates using sndsgd\util\Path::test
 */
class PathTest extends \sndsgd\field\Rule
{
   /**
    * The bitmask to pass to sndsgd\util\Path::test
    *
    * @var integer
    */
   protected $bitmask = 1;

   /**
    * @param string $bitmask The bitmask to pass to sndsgd\util\Path::test
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
    */
   public function validate(
      $value, 
      $name = null, 
      $index = null, 
      Collection $collection = null
   )
   {
      $path = Path::normalize($value);
      $test = Path::test($path, $this->bitmask);
      return ($test === true)
         ? $path
         : new ValidationError($test, $value, $name, $index);
   }
}

