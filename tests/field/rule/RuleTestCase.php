<?php

namespace sndsgd\field\rule;


/**
 * @coverageIgnore
 */
abstract class RuleTestCase extends \PHPUnit_Framework_TestCase
{
   protected static $class = "\\sndsgd\\field\\ValidationError";

   public function assertValidationError($value)
   {
      $this->assertInstanceOf(static::$class, $value);
   }

   public function assertValid($value)
   {
      $this->assertNotInstanceOf(static::$class, $value);
   }
}

