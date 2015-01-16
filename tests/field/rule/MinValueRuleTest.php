<?php

namespace sndsgd\field\rule;


class MinValueRuleTest extends \PHPUnit_Framework_TestCase
{
   /**
    * @expectedException InvalidArgumentException
    */
   public function testNullMinException()
   {
      new MinValueRule();
   }

   /**
    * @expectedException InvalidArgumentException
    */
   public function testBadMinException()
   {
      new MinValueRule('hello');
   }

   public function testGetMessage()
   {
      $rule = new MinValueRule(10);
      $rule->setMessage('{{len}}');
      $error = $rule->getError();
      $this->assertEquals('10', $error->getMessage());
   }

   public function testSuccess()
   {
      $rule = new MinValueRule(10);

      $rule->setValue(10);
      $this->assertTrue($rule->validate());

      $rule->setValue(10.0001);
      $this->assertTrue($rule->validate());

      $rule->setValue(10000);
      $this->assertTrue($rule->validate());

      $rule->setValue(2e10);
      $this->assertTrue($rule->validate());
   }

   public function testFailure()
   {
      $rule = new MinValueRule(10);

      $rule->setValue(9.999999);
      $this->assertFalse($rule->validate());

      $rule->setValue(-100);
      $this->assertFalse($rule->validate());

      $rule->setValue(0);
      $this->assertFalse($rule->validate(0));
   }
}

