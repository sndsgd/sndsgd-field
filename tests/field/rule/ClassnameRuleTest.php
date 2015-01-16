<?php

namespace sndsgd\field\rule;


class ClassnameRuleTest extends \PHPUnit_Framework_TestCase
{
   public function testSuccess()
   {
      $rule = new ClassnameRule;

      $rule->setValue('yep');
      $this->assertTrue($rule->validate());
      $this->assertEquals('yep', $rule->getValue());

      $rule->setValue('Yep');
      $this->assertTrue($rule->validate());
      $this->assertEquals('Yep', $rule->getValue());

      $rule->setValue('\yep');
      $this->assertTrue($rule->validate());
      $this->assertEquals('yep', $rule->getValue());

      $rule->setValue('\yep\it_is0');
      $this->assertTrue($rule->validate());
      $this->assertEquals('yep\it_is0', $rule->getValue());

      # sndsgd\Classname trims excess chars
      $rule->setValue('yep\\');
      $this->assertTrue($rule->validate());
      $this->assertEquals('yep', $rule->getValue());
   }

   public function testFailure()
   {
      $rule = new ClassnameRule;

      $rule->setValue('0nope');
      $this->assertFalse($rule->validate());

      $rule->setValue('\looksOkay\0fail');
      $this->assertFalse($rule->validate());

      $rule->setValue('');
      $this->assertFalse($rule->validate());

      $rule->setValue([]);
      $this->assertFalse($rule->validate());
   }
}

