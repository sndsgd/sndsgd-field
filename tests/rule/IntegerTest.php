<?php

namespace sndsgd\field\rule;

use \sndsgd\field\ValidationError;


class IntegerTest extends \PHPUnit_Framework_TestCase
{
   public function testDateString()
   {
      $rule = new Integer;
      #$this->assertEquals(0, $rule->validate(0));
      $this->assertEquals(42, $rule->validate(42));
      $this->assertEquals(42, $rule->validate('42'));
      $this->assertEquals(-42, $rule->validate(-42));
      $this->assertEquals(-42, $rule->validate('-42'));
      
      $this->assertTrue($rule->validate(4.2) instanceof ValidationError);
      $this->assertTrue($rule->validate('4.2') instanceof ValidationError);
      $this->assertTrue($rule->validate('-4.2') instanceof ValidationError);
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

