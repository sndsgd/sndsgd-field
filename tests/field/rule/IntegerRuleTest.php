<?php

namespace sndsgd\field\rule;

use \StdClass;


class IntegerRuleTest extends \PHPUnit_Framework_TestCase
{
   public function testSuccess()
   {
      $rule = new IntegerRule;

      $rule->setValue(0);
      $this->assertTrue($rule->validate());
      $this->assertEquals(0, $rule->getValue());

      $rule->setValue(42);
      $this->assertTrue($rule->validate());
      $this->assertEquals(42, $rule->getValue());

      $rule->setValue('42');
      $this->assertTrue($rule->validate());
      $this->assertEquals(42, $rule->getValue());

      $rule->setValue(-42);
      $this->assertTrue($rule->validate());
      $this->assertEquals(-42, $rule->getValue());
      
      $rule->setValue('-42');
      $this->assertTrue($rule->validate());
      $this->assertEquals(-42, $rule->getValue());  
   }

   public function testFailure()
   {
      $rule = new IntegerRule;

      $rule->setValue(4.2);
      $this->assertFalse($rule->validate(4.2));

      $rule->setValue('4.2');
      $this->assertFalse($rule->validate('4.2'));

      $rule->setValue('-4.2');
      $this->assertFalse($rule->validate());

      $rule->setValue('abc');
      $this->assertFalse($rule->validate());

      $rule->setValue('a42');
      $this->assertFalse($rule->validate());

      $rule->setValue('');
      $this->assertFalse($rule->validate());

      $rule->setValue(' ');
      $this->assertFalse($rule->validate());

      $rule->setValue(true);
      $this->assertFalse($rule->validate());

      $rule->setValue(false);
      $this->assertFalse($rule->validate());

      $rule->setValue([]);
      $this->assertFalse($rule->validate());

      $rule->setValue(new StdClass);
      $this->assertFalse($rule->validate());
   }
}

