<?php

namespace sndsgd\field\rule;

use \sndsgd\field\ValidationError;


class AlphaNumTest extends RuleTestCase
{
   public function test()
   {
      $rule = new AlphaNum;

      # success
      $this->assertValid($rule->validate('abcdefghijklmno'));
      $this->assertValid($rule->validate('0'));

      # failure
      $this->assertValidationError($rule->validate('asd-asd'));
   }
}

