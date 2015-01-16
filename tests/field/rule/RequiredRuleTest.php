<?php

namespace sndsgd\field\rule;

use \sndsgd\field\rule\RequiredRule;


class RequiredRuleTest extends \PHPUnit_Framework_TestCase
{
   public function testValidate()
   {
      $rule = new RequiredRule;

      $this->assertFalse($rule->validate());

      $rule->setValue('');
      $this->assertFalse($rule->validate());

      $rule->setValue(1);
      $this->assertTrue($rule->validate());
   }
}

