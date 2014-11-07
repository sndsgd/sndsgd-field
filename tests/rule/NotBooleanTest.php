<?php

namespace sndsgd\field\rule;

use \sndsgd\field\ValidationError;


class NotBooleanTest extends \PHPUnit_Framework_TestCase
{
   public function testNotBoolean()
   {
      $rule = new NotBoolean;
      $this->assertTrue($rule->validate(true) instanceof ValidationError);
      $this->assertTrue($rule->validate(false) instanceof ValidationError);

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

