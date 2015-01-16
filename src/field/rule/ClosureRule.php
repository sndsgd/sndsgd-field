<?php

namespace sndsgd\field\rule;

use \Exception;
use \InvalidArgumentException;
use \sndsgd\Field;
use \sndsgd\field\Collection;
use \sndsgd\field\Error;


/**
 * Ensure a value doesn't contain too many characters
 */
class ClosureRule extends \sndsgd\field\Rule
{
   /**
    * {@inheritdoc}
    */
   protected $message = '';

   /**
    * The handler function
    *
    * @var callable
    */
   protected $fn;

   /**
    * @param callable $handler A function/method to perform validation
    */
   public function __construct($handler = null)
   {
      if ($handler === null || !is_callable($handler)) {
         throw new InvalidArgumentException(
            "invalid value provided for 'handler'; ".
            "expecting a closure"
         );
      }
      $this->fn = $handler;
   }

   /**
    * Ensure that multiple Closure rules can be added to a field
    *
    * @return string
    */
   public function getClass()
   {
      if (is_string($this->fn)) {
         return $this->fn;
      }

      $rand = mt_rand().' '.microtime(true);
      return 'sndsgd\field\rule\Closure('.sha1($rand).')';
   }

   /**
    * {@inheritdoc}
    */
   public function validate()
   {
      $result = call_user_func(
         $this->fn, 
         $this->value,
         $this->valueIndex,
         $this->field, 
         $this->collection
      );

      if (is_array($result)) {
         $this->value = $result[1];
         $result = $result[0];
      }

      return $result;
   }
}

