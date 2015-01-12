<?php

namespace sndsgd\field\rule;


class MinValueTest extends \sndsgd\field\RuleTestCase
{
   public function test()
   {
      $rule = new MinValue(10);

      # success
      $this->assertValid($rule->validate(10));
      $this->assertValid($rule->validate(10.0001));
      $this->assertValid($rule->validate(10000));
      $this->assertValid($rule->validate(2e10));

      # failure
      $this->assertValidationError($rule->validate(9.999999));
      $this->assertValidationError($rule->validate(-100));
      $this->assertValidationError($rule->validate(0));
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

