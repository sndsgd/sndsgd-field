<?php

namespace sndsgd\field\rule;


class AlphaRuleTest extends \PHPUnit_Framework_TestCase
{
   public function testSuccess()
   {
      $rule = new AlphaRule;

      $rule->setValue('abcdefghijklmno');
      $this->assertTrue($rule->validate());
   }

   public function testFailure()
   {
      $rule = new AlphaRule;
      
      $rule->setValue('0');
      $this->assertFalse($rule->validate());

      $rule->setValue(0);
      $this->assertFalse($rule->validate());

      $rule->setValue('asd-asd');
      $this->assertFalse($rule->validate());
   }
}

