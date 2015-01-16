<?php

namespace sndsgd\field\rule;


class BooleanRuleTest extends \PHPUnit_Framework_TestCase
{
   public function testSuccess()
   {
      $rule = new BooleanRule;

      $rule->setValue(true);
      $this->assertTrue($rule->validate());
      $this->assertTrue($rule->getValue());

      $rule->setValue('true');
      $this->assertTrue($rule->validate());
      $this->assertTrue($rule->getValue());

      $rule->setValue('on');
      $this->assertTrue($rule->validate());
      $this->assertTrue($rule->getValue());

      $rule->setValue(1);
      $this->assertTrue($rule->validate());
      $this->assertTrue($rule->getValue());

      $rule->setValue('1');
      $this->assertTrue($rule->validate());
      $this->assertTrue($rule->getValue());

      $rule->setValue(false);
      $this->assertTrue($rule->validate());
      $this->assertFalse($rule->getValue());

      $rule->setValue('false');
      $this->assertTrue($rule->validate());
      $this->assertFalse($rule->getValue());

      $rule->setValue('off');
      $this->assertTrue($rule->validate());
      $this->assertFalse($rule->getValue());

      $rule->setValue(0);
      $this->assertTrue($rule->validate());
      $this->assertFalse($rule->getValue());

      $rule->setValue('0');
      $this->assertTrue($rule->validate());
      $this->assertFalse($rule->getValue());
   }

   public function testFailure()
   {
      $rule = new BooleanRule;

      $rule->setValue([]);
      $this->assertFalse($rule->validate());

      $rule->setValue('russell');
      $this->assertFalse($rule->validate());
   }
}

