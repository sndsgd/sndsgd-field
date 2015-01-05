<?php

namespace sndsgd\field\rule;

use \sndsgd\field\ValidationError;
use \sndsgd\util\Dir;
use \sndsgd\util\File;


class PathTestTest extends RuleTestCase
{
   public function test()
   {
      $rule = new PathTest(Dir::WRITABLE);

      # success
      $this->assertValid($rule->validate(sys_get_temp_dir()));

      # failure
      $this->assertValidationError($rule->validate('/___nope___'));


      $rule = new PathTest(File::READABLE);
      $this->assertValid($rule->validate(__FILE__));
      $this->assertValidationError($rule->validate(__DIR__));
   }

   /**
    * @expectedException InvalidArgumentException
    */
   public function testNullBitmaskException()
   {
      new PathTest();
   }

   /**
    * @expectedException InvalidArgumentException
    */
   public function testInvalidArgException()
   {
      new PathTest('hello');
   }
}

