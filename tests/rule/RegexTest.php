<?php

namespace sndsgd\field\rule;

use \sndsgd\field\ValidationError;


class RegexTest extends \PHPUnit_Framework_TestCase
{
   public function test()
   {
      $rule = new Regex('/[A-Z][a-z]+/');

      # success
      $this->assertFalse($rule->validate('Hello') instanceof ValidationError);

      # failure
      $this->assertTrue($rule->validate('hello') instanceof ValidationError);
   }

   /**
    * @expectedException InvalidArgumentException
    */
   public function testNullRegexException()
   {
      new Regex();
   }

   /**
    * @expectedException InvalidArgumentException
    */
   public function testBadRegexException()
   {
      new Regex('/[a-z]');
   }
}

