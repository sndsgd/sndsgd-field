<?php

namespace sndsgd\field\rule;


class MaxLengthRuleTest extends \PHPUnit_Framework_TestCase
{
   /**
    * @expectedException InvalidArgumentException
    */
   public function testNullMaxException()
   {
      new MaxLengthRule();
   }

   /**
    * @expectedException InvalidArgumentException
    */
   public function testBadMaxException()
   {
      new MaxLengthRule('hello');
   }

   public function testGetMessage()
   {
      $rule = new MaxLengthRule(10);
      $rule->setMessage('{{len}} {{what}}');
      $error = $rule->getError();
      $this->assertEquals('10 characters', $error->getMessage());
   }

   public function testSuccess()
   {
      $rule = new MaxLengthRule(3);

      $rule->setValue('a');
      $this->assertTrue($rule->validate());

      $rule->setValue('ab');
      $this->assertTrue($rule->validate());

      $rule->setValue('abc');
      $this->assertTrue($rule->validate());
   }

   public function testFailure()
   {
      $rule = new MaxLengthRule(3);   
      
      $rule->setValue('abcd');
      $this->assertFalse($rule->validate());

      $rule->setValue('abcde');
      $this->assertFalse($rule->validate());

      $rule->setValue('abcdef');
      $this->assertFalse($rule->validate());
   }
}

