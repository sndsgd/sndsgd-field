<?php

namespace sndsgd\field\rule;

use \StdClass;


class IntegerTest extends \sndsgd\field\RuleTestCase
{
   public function testDateString()
   {
      $rule = new Integer;
      $this->assertEquals(0, $rule->validate(0));
      $this->assertEquals(42, $rule->validate(42));
      $this->assertEquals(42, $rule->validate('42'));
      $this->assertEquals(-42, $rule->validate(-42));
      $this->assertEquals(-42, $rule->validate('-42'));
      
      $this->assertValidationError($rule->validate(4.2));
      $this->assertValidationError($rule->validate('4.2'));
      $this->assertValidationError($rule->validate('-4.2'));
      $this->assertValidationError($rule->validate('abc'));
      $this->assertValidationError($rule->validate('a42'));
      $this->assertValidationError($rule->validate(''));
      $this->assertValidationError($rule->validate(' '));
      $this->assertValidationError($rule->validate(true));
      $this->assertValidationError($rule->validate(false));
      $this->assertValidationError($rule->validate([]));
      $this->assertValidationError($rule->validate(new StdClass));
   }
}

