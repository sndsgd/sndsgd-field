<?php

namespace sndsgd\field\rule;

use \sndsgd\field\ValidationError;


class EmailTest extends \PHPUnit_Framework_TestCase
{
   public function testNotBoolean()
   {
      $rule = new Email;

      $this->assertFalse($rule->validate('r@snds.gd') instanceof ValidationError);
      $this->assertFalse($rule->validate('name@domain.tld') instanceof ValidationError);
      $this->assertFalse($rule->validate('name@domain.co.uk') instanceof ValidationError);

      $this->assertTrue($rule->validate('nope') instanceof ValidationError);
      $this->assertTrue($rule->validate('0') instanceof ValidationError);
      $this->assertTrue($rule->validate(0) instanceof ValidationError);
      $this->assertTrue($rule->validate([]) instanceof ValidationError);
   }
}

