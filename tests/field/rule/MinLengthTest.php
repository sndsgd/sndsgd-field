<?php

namespace sndsgd\field\rule;

use \sndsgd\field\ValidationError;


class MinLengthTest extends RuleTestCase
{
   public function test()
   {
      $rule = new MinLength(3);

      # success
      $this->assertValid($rule->validate('abc'));
      $this->assertValid($rule->validate('abcd'));
      $this->assertValid($rule->validate('abcde'));
   

      # failure
      $this->assertValidationError($rule->validate('ab'));
      $this->assertValidationError($rule->validate('a'));
      $this->assertValidationError($rule->validate(''));
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

