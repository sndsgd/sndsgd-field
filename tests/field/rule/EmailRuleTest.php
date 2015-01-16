<?php

namespace sndsgd\field\rule;

use \StdClass;


class EmailRuleTest extends \PHPUnit_Framework_TestCase
{
   public function testSuccess()
   {
      $rule = new EmailRule;

      $rule->setValue('r@snds.gd');
      $this->assertTrue($rule->validate());
      $this->assertEquals('r@snds.gd', $rule->getValue());

      $rule->setValue('name@domain.tld');
      $this->assertTrue($rule->validate());
      $this->assertEquals('name@domain.tld', $rule->getValue());

      $rule->setValue('name@domain.co.uk');
      $this->assertTrue($rule->validate());
      $this->assertEquals('name@domain.co.uk', $rule->getValue());
   }

   public function testFailure()
   {
      $rule = new EmailRule;

      $rule->setValue('nope');
      $this->assertFalse($rule->validate());

      $rule->setValue('0');
      $this->assertFalse($rule->validate());

      $rule->setValue(0);
      $this->assertFalse($rule->validate());

      $rule->setValue([]);
      $this->assertFalse($rule->validate());

      $rule->setValue(new StdClass);
      $this->assertFalse($rule->validate());
   }
}

