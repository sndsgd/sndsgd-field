<?php

namespace sndsgd\field\rule;


class MaxLengthTest extends \sndsgd\field\RuleTestCase
{
   public function test()
   {
      $rule = new MaxLength(3);

      # success
      $this->assertValid($rule->validate('abc'));
      $this->assertValid($rule->validate('ab'));
      $this->assertValid($rule->validate('a'));
   

      # failure
      $this->assertValidationError($rule->validate('abcd'));
      $this->assertValidationError($rule->validate('abcde'));
      $this->assertValidationError($rule->validate('abcdef'));
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

