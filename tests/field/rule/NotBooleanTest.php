<?php

namespace sndsgd\field\rule;


class NotBooleanTest extends \sndsgd\field\RuleTestCase
{
   public function testNotBoolean()
   {
      $rule = new NotBoolean;
      $this->assertValidationError($rule->validate(true));
      $this->assertValidationError($rule->validate(false));

      $this->assertTrue($rule->validate('true') === 'true');
      $this->assertTrue($rule->validate('TRUE') === 'TRUE');
      $this->assertTrue($rule->validate('false') === 'false');
      $this->assertTrue($rule->validate('FALSE') === 'FALSE');
      $this->assertTrue($rule->validate('on') === 'on');
      $this->assertTrue($rule->validate('off') === 'off');
      $this->assertTrue($rule->validate('1') === '1');
      $this->assertTrue($rule->validate('0') === '0');
      $this->assertTrue($rule->validate(1) === 1);
      $this->assertTrue($rule->validate(0) === 0);
      $this->assertTrue($rule->validate([]) === []);
   }
}

