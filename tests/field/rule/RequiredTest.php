<?php

namespace sndsgd\field\rule;


class RequiredTest extends \sndsgd\field\RuleTestCase
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

