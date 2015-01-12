<?php

namespace sndsgd\field\rule;


class RegexTest extends \sndsgd\field\RuleTestCase
{
   public function test()
   {
      $rule = new Regex('/[A-Z][a-z]+/');

      # success
      $this->assertValid($rule->validate('Hello'));

      # failure
      $this->assertValidationError($rule->validate('hello'));
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

