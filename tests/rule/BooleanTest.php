<?php

namespace sndsgd\field\rule;

use \sndsgd\field\ValidationError;


class BooleanTest extends \PHPUnit_Framework_TestCase
{
   public function testNotBoolean()
   {
      $rule = new Boolean;

      $this->assertTrue($rule->validate(true));
      $this->assertTrue($rule->validate('true'));
      $this->assertTrue($rule->validate('on'));
      $this->assertTrue($rule->validate(1));
      $this->assertTrue($rule->validate('1'));
      $this->assertFalse($rule->validate(false));
      $this->assertFalse($rule->validate('false'));
      $this->assertFalse($rule->validate('off'));
      $this->assertFalse($rule->validate(0));
      $this->assertFalse($rule->validate('0'));


      $this->assertTrue($rule->validate([]) instanceof ValidationError);
      $this->assertTrue($rule->validate('russell') instanceof ValidationError);
   }
}

