<?php

namespace sndsgd\field\rule;

use \sndsgd\field\ValidationError;


class MaxLengthTest extends \PHPUnit_Framework_TestCase
{
   public function test()
   {
      $rule = new MaxLength(3);

      # success
      $this->assertFalse($rule->validate('abc') instanceof ValidationError);
      $this->assertFalse($rule->validate('ab') instanceof ValidationError);
      $this->assertFalse($rule->validate('a') instanceof ValidationError);
   

      # failure
      $this->assertTrue($rule->validate('abcd') instanceof ValidationError);
      $this->assertTrue($rule->validate('abcde') instanceof ValidationError);
      $this->assertTrue($rule->validate('abcdef') instanceof ValidationError);
   }

   /**
    * @expectedException InvalidArgumentException
    */
   public function testNullMaxException()
   {
      new MaxLength();
   }

   /**
    * @expectedException InvalidArgumentException
    */
   public function testBadMaxException()
   {
      new MaxLength('hello');
   }
}

