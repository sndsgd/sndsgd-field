<?php

namespace sndsgd\field\rule;

use \sndsgd\field\ValidationError;


class FloatTest extends \PHPUnit_Framework_TestCase
{
   public function testFloat()
   {
      $rule = new Float;
      $this->assertEquals(42, $rule->validate(42));
      $this->assertEquals(42, $rule->validate('42'));
      $this->assertEquals(-42, $rule->validate(-42));
      $this->assertEquals(-42, $rule->validate('-42'));
      $this->assertEquals(4.2, $rule->validate(4.2));
      $this->assertEquals(4.2, $rule->validate('4.2'));
      $this->assertEquals(-4.2, $rule->validate('-4.2'));

      $this->assertTrue($rule->validate('abc') instanceof ValidationError);
      $this->assertTrue($rule->validate('a42') instanceof ValidationError);
      $this->assertTrue($rule->validate('') instanceof ValidationError);
      $this->assertTrue($rule->validate(' ') instanceof ValidationError);
      $this->assertTrue($rule->validate(true) instanceof ValidationError);
      $this->assertTrue($rule->validate(false) instanceof ValidationError);
      $this->assertTrue($rule->validate([]) instanceof ValidationError);
      $this->assertTrue($rule->validate(new \StdClass) instanceof ValidationError);
   }
}

