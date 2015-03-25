<?php

namespace sndsgd\field\rule;

use \Closure;
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
   protected $message = "";

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
      if (!is_string($handler)) {
         $handler = $handler->bindTo($this, $this);
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
      $hash = sha1(mt_rand().microtime(true));
      return "sndsgd\\field\\rule\\Closure($hash)";
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

