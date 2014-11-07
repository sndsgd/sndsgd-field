<?php

namespace sndsgd\field\rule;

use \sndsgd\field\ValidationError;


class MinLengthTest extends \PHPUnit_Framework_TestCase
{
   public function test()
   {
      $rule = new MinLength(3);

      # success
      $this->assertFalse($rule->validate('abc') instanceof ValidationError);
      $this->assertFalse($rule->validate('abcd') instanceof ValidationError);
      $this->assertFalse($rule->validate('abcde') instanceof ValidationError);
   

      # failure
      $this->assertTrue($rule->validate('ab') instanceof ValidationError);
      $this->assertTrue($rule->validate('a') instanceof ValidationError);
      $this->assertTrue($rule->validate('') instanceof ValidationError);
   }

   /**
    * @expectedException InvalidArgumentException
    */
   public function testNullMaxException()
   {
      new MinLength();
   }

   /**
    * @expectedException InvalidArgumentException
    */
   public function testBadMaxException()
   {
      new MinLength('hello');
   }
}

