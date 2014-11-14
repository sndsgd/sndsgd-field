<?php

namespace sndsgd\field\rule;

use \sndsgd\field\ValidationError;
use \sndsgd\util\Dir;
use \sndsgd\util\File;


class PathTestTest extends \PHPUnit_Framework_TestCase
{
   public function test()
   {
      $rule = new PathTest(Dir::WRITABLE);

      # success
      $this->assertFalse($rule->validate(sys_get_temp_dir()) instanceof ValidationError);

      # failure
      $this->assertTrue($rule->validate('/___nope___') instanceof ValidationError);


      $rule = new PathTest(File::READABLE);
      $this->assertFalse($rule->validate(__FILE__) instanceof ValidationError);
      $this->assertTrue($rule->validate(__DIR__) instanceof ValidationError);
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

