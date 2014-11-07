<?php

namespace sndsgd\field\rule;

use \DateTime;
use \sndsgd\field\ValidationError;


class DateStringTest extends \PHPUnit_Framework_TestCase
{
   public function testDateString()
   {
      $rule = new DateString;
      $this->assertTrue($rule->validate('October 14th, 2014') instanceof DateTime);
      $this->assertTrue($rule->validate('2008-08-07 18:11:31') instanceof DateTime);
      $this->assertTrue($rule->validate('08-08-07 18:11:31') instanceof DateTime);

      $this->assertTrue($rule->validate(42) instanceof ValidationError);
      $this->assertTrue($rule->validate('42') instanceof ValidationError);
      $this->assertTrue($rule->validate('') instanceof ValidationError);
      $this->assertTrue($rule->validate(' ') instanceof ValidationError);
      $this->assertTrue($rule->validate(true) instanceof ValidationError);
      $this->assertTrue($rule->validate(false) instanceof ValidationError);
      $this->assertTrue($rule->validate([]) instanceof ValidationError);
      $this->assertTrue($rule->validate(new \StdClass) instanceof ValidationError);
   }
}

