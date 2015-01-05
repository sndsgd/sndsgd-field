<?php

namespace sndsgd\field\rule;

use \sndsgd\field\ValidationError;


class AlphaTest extends RuleTestCase
{
   public function test()
   {
      $rule = new Alpha;

      # success
      $this->assertValid($rule->validate('abcdefghijklmno'));
      
      # failure
      $this->assertValidationError($rule->validate('0'));
      $this->assertValidationError($rule->validate(0));
      $this->assertValidationError($rule->validate('asd-asd'));
   }
}

