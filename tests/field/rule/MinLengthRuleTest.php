<?php

namespace sndsgd\field\rule;


class MinLengthRuleTest extends \PHPUnit_Framework_TestCase
{
   /**
    * @expectedException InvalidArgumentException
    */
   public function testNullMaxException()
   {
      new MinLengthRule();
   }

   /**
    * @expectedException InvalidArgumentException
    */
   public function testBadMaxException()
   {
      new MinLengthRule('hello');
   }

   public function testGetMessage()
   {
      $rule = new MinLengthRule(10);
      $rule->setMessage('{{len}} {{what}}');
      $error = $rule->getError();
      $this->assertEquals('10 characters', $error->getMessage());
   }

   public function testSuccess()
   {
      $rule = new MinLengthRule(3);

      $rule->setValue('abc');
      $this->assertTrue($rule->validate());

      $rule->setValue('abcd');
      $this->assertTrue($rule->validate());

      $rule->setValue('abcde');
      $this->assertTrue($rule->validate());
   }

   public function testFailure()
   {
      $rule = new MinLengthRule(3);   
      
      $rule->setValue('ab');
      $this->assertFalse($rule->validate());

      $rule->setValue('a');
      $this->assertFalse($rule->validate());

      $rule->setValue('');
      $this->assertFalse($rule->validate());
   }
}

