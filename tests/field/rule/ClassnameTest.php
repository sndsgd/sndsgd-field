<?php

namespace sndsgd\field\rule;


class ClassnameTest extends \sndsgd\field\RuleTestCase
{
   public function testRule()
   {
      $rule = new Classname;

      $this->assertValid($rule->validate('yep'));
      $this->assertValid($rule->validate('Yep'));
      $this->assertValid($rule->validate('\yep'));
      $this->assertValid($rule->validate('\yep\it_is0'));

      # sndsgd\Classname trims excess chars
      $this->assertValid($rule->validate('yep\\'));


      $this->assertValidationError($rule->validate('0nope'));
      $this->assertValidationError($rule->validate('\looksOkay\0fail'));
      $this->assertValidationError($rule->validate(''));
      $this->assertValidationError($rule->validate([]));
   }
}

