<?php

namespace sndsgd\field\rule;

use \sndsgd\field\ValidationError;


class MaxValueTest extends \PHPUnit_Framework_TestCase
{
   public function test()
   {
      $rule = new MaxValue(10);

      # success
      $this->assertFalse($rule->validate(9) instanceof ValidationError);
      $this->assertFalse($rule->validate(9.9999) instanceof ValidationError);
      $this->assertFalse($rule->validate(10) instanceof ValidationError);
      $this->assertFalse($rule->validate(-1.1) instanceof ValidationError);

      # failure
      $this->assertTrue($rule->validate(10.0001) instanceof ValidationError);
      $this->assertTrue($rule->validate(11) instanceof ValidationError);
      $this->assertTrue($rule->validate(1000) instanceof ValidationError);
   }

   /**
    * @expectedException InvalidArgumentException
    */
   public function testNullMaxException()
   {
      new MaxValue();
   }

   /**
    * @expectedException InvalidArgumentException
    */
   public function testBadMaxException()
   {
      new MaxValue('hello');
   }
}

