<?php

namespace sndsgd\field\rule;


class MaxValueRuleTest extends \PHPUnit_Framework_TestCase
{
   /**
    * @expectedException InvalidArgumentException
    */
   public function testNullMaxException()
   {
      new MaxValueRule();
   }

   /**
    * @expectedException InvalidArgumentException
    */
   public function testBadMaxException()
   {
      new MaxValueRule('hello');
   }

   public function testGetMessage()
   {
      $rule = new MaxValueRule(10);
      $rule->setMessage('{{len}}');
      $error = $rule->getError();
      $this->assertEquals('10', $error->getMessage());
   }

   public function testSuccess()
   {
      $rule = new MaxValueRule(10);

      $rule->setValue(9);
      $this->assertTrue($rule->validate());

      $rule->setValue(9.9999);
      $this->assertTrue($rule->validate());

      $rule->setValue(10);
      $this->assertTrue($rule->validate());

      $rule->setValue(-1.1);
      $this->assertTrue($rule->validate());
   }

   public function testFailure()
   {
      $rule = new MaxValueRule(10);

      $rule->setValue(10.0001);
      $this->assertFalse($rule->validate());

      $rule->setValue(11);
      $this->assertFalse($rule->validate());

      $rule->setValue(100);
      $this->assertFalse($rule->validate());
   }
}

