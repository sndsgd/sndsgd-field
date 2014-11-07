<?php

namespace sndsgd\field\rule;

use \sndsgd\field\ValidationError;


class MinValueTest extends \PHPUnit_Framework_TestCase
{
   public function test()
   {
      $rule = new MinValue(10);

      # success
      $this->assertFalse($rule->validate(10) instanceof ValidationError);
      $this->assertFalse($rule->validate(10.0001) instanceof ValidationError);
      $this->assertFalse($rule->validate(10000) instanceof ValidationError);
      $this->assertFalse($rule->validate(2e10) instanceof ValidationError);

      # failure
      $this->assertTrue($rule->validate(9.999999) instanceof ValidationError);
      $this->assertTrue($rule->validate(-100) instanceof ValidationError);
      $this->assertTrue($rule->validate(0) instanceof ValidationError);
   }

   /**
    * @expectedException InvalidArgumentException
    */
   public function testNullMinException()
   {
      new MinValue();
   }

   /**
    * @expectedException InvalidArgumentException
    */
   public function testBadMinException()
   {
      new MinValue('hello');
   }
}

