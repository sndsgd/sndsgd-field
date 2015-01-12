<?php

namespace sndsgd\field;

use \sndsgd\field\ValidationError;
use \sndsgd\field\rule\Required as RequiredRule;


/**
 * Use this class to test all instances of sndsgd\field\Rule
 * 
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



class RuleTest extends \PHPUnit_Framework_TestCase
{
   public function testSetMessage()
   {
      $rule = new RequiredRule;
      $rule->setMessage('yep, required');

      $result = $rule->validate('');
      $this->assertTrue($result instanceof ValidationError);

      $this->assertEquals('yep, required', $result->getMessage());
   }

   /**
    * @expectedException InvalidArgumentException
    */
   public function testConstructorException()
   {
      $rule = new RequiredRule(42);
   }

   /**
    * @expectedException InvalidArgumentException
    */
   public function testSetMessageException()
   {
      $rule = new RequiredRule;
      $rule->setMessage(42);
   }
}


