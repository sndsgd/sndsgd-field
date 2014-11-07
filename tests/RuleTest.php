<?php

use \sndsgd\field\ValidationError;
use \sndsgd\field\rule\Required as RequiredRule;


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


