<?php

namespace sndsgd\field\rule;

use \sndsgd\field\ValidationError;


class RequiredTest extends \PHPUnit_Framework_TestCase
{
   public function test()
   {
      $rule = new Required;

      # success
      $this->assertFalse($rule->validate('hello') instanceof ValidationError);
      $this->assertFalse($rule->validate('1') instanceof ValidationError);
      $this->assertFalse($rule->validate('2') instanceof ValidationError);
      $this->assertFalse($rule->validate('2') instanceof ValidationError);

      # failure
      $this->assertTrue($rule->validate('') instanceof ValidationError);
      $this->assertTrue($rule->validate(null) instanceof ValidationError);
   }
}

