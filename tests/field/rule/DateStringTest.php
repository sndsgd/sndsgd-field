<?php

namespace sndsgd\field\rule;

use \DateTime;
use \StdClass;


class DateStringTest extends \sndsgd\field\RuleTestCase
{
   public function testDateString()
   {
      $rule = new DateString;
      $this->assertValid($rule->validate('October 14th, 2014'));
      $this->assertValid($rule->validate('2008-08-07 18:11:31'));
      $this->assertValid($rule->validate('08-08-07 18:11:31'));

      $this->assertValidationError($rule->validate(42));
      $this->assertValidationError($rule->validate('42'));
      $this->assertValidationError($rule->validate(''));
      $this->assertValidationError($rule->validate(' '));
      $this->assertValidationError($rule->validate(true));
      $this->assertValidationError($rule->validate(false));
      $this->assertValidationError($rule->validate([]));
      $this->assertValidationError($rule->validate(new StdClass));
   }
}

