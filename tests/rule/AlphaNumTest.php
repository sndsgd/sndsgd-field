<?php

namespace sndsgd\field\rule;

use \sndsgd\field\ValidationError;


class AlphaNumTest extends \PHPUnit_Framework_TestCase
{
   public function test()
   {
      $rule = new AlphaNum;

      # success
      $this->assertFalse($rule->validate('abcdefghijklmno') instanceof ValidationError);
      $this->assertFalse($rule->validate('0') instanceof ValidationError);

      # failure
      $this->assertTrue($rule->validate('asd-asd') instanceof ValidationError);
   }
}

