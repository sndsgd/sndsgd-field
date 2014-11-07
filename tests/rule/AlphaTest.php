<?php

namespace sndsgd\field\rule;

use \sndsgd\field\ValidationError;


class AlphaTest extends \PHPUnit_Framework_TestCase
{
   public function test()
   {
      $rule = new Alpha;

      # success
      $this->assertFalse($rule->validate('abcdefghijklmno') instanceof ValidationError);
      
      # failure
      $this->assertTrue($rule->validate('0') instanceof ValidationError);
      $this->assertTrue($rule->validate(0) instanceof ValidationError);
      $this->assertTrue($rule->validate('asd-asd') instanceof ValidationError);
   }
}

