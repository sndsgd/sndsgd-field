<?php

namespace sndsgd\field\rule;


class EmailTest extends \sndsgd\field\RuleTestCase
{
   public function testNotBoolean()
   {
      $rule = new Email;

      $this->assertValid($rule->validate('r@snds.gd'));
      $this->assertValid($rule->validate('name@domain.tld'));
      $this->assertValid($rule->validate('name@domain.co.uk'));

      $this->assertValidationError($rule->validate('nope'));
      $this->assertValidationError($rule->validate('0'));
      $this->assertValidationError($rule->validate(0));
      $this->assertValidationError($rule->validate([]));
   }
}

