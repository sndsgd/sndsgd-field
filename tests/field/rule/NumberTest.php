<?php

namespace sndsgd\field\rule;

use \StdClass;



class NumberTest extends \sndsgd\field\RuleTestCase
{
   public function testFloat()
   {
      $rule = new Number;
      $this->assertEquals(42, $rule->validate(42));
      $this->assertEquals(42, $rule->validate('42'));
      $this->assertEquals(-42, $rule->validate(-42));
      $this->assertEquals(-42, $rule->validate('-42'));
      $this->assertEquals(4.2, $rule->validate(4.2));
      $this->assertEquals(4.2, $rule->validate('4.2'));
      $this->assertEquals(-4.2, $rule->validate('-4.2'));

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

