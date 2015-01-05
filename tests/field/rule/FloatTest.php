<?php

namespace sndsgd\field\rule;

use \sndsgd\field\ValidationError;


class FloatTest extends RuleTestCase
{
   public function testFloat()
   {
      $rule = new Float;
      $this->assertValid($rule->validate(42));
      $this->assertValid($rule->validate('42'));
      $this->assertValid($rule->validate(-42));
      $this->assertValid($rule->validate('-42'));
      $this->assertValid($rule->validate(4.2));
      $this->assertValid($rule->validate('4.2'));
      $this->assertValid($rule->validate('-4.2'));

      $this->assertValidationError($rule->validate('abc'));
      $this->assertValidationError($rule->validate('a42'));
      $this->assertValidationError($rule->validate(''));
      $this->assertValidationError($rule->validate(' '));
      $this->assertValidationError($rule->validate(true));
      $this->assertValidationError($rule->validate(false));
      $this->assertValidationError($rule->validate([]));
      $this->assertValidationError($rule->validate(new \StdClass));
   }
}

