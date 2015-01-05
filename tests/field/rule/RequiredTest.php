<?php

namespace sndsgd\field\rule;

use \sndsgd\field\ValidationError;


class RequiredTest extends RuleTestCase
{
   public function test()
   {
      $rule = new Required;

      # success
      $this->assertValid($rule->validate('hello'));
      $this->assertValid($rule->validate('1'));
      $this->assertValid($rule->validate('2'));
      $this->assertValid($rule->validate('2'));

      # failure
      $this->assertValidationError($rule->validate(''));
      $this->assertValidationError($rule->validate(null));
   }
}

