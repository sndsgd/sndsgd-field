<?php

namespace sndsgd\field\rule;


class MaxValueTest extends \sndsgd\field\RuleTestCase
{
   public function test()
   {
      $rule = new MaxValue(10);

      # success
      $this->assertValid($rule->validate(9));
      $this->assertValid($rule->validate(9.9999));
      $this->assertValid($rule->validate(10));
      $this->assertValid($rule->validate(-1.1));

      # failure
      $this->assertValidationError($rule->validate(10.0001));
      $this->assertValidationError($rule->validate(11));
      $this->assertValidationError($rule->validate(1000));
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

