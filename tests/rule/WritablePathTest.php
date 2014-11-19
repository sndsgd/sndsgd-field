<?php

namespace sndsgd\field\rule;

use \sndsgd\field\ValidationError;
use \sndsgd\util\Dir;
use \sndsgd\util\File;


class WritablePathTest extends \PHPUnit_Framework_TestCase
{
   public function testDir()
   {
      $rule = new WritablePath(true);

      # success
      $test = sys_get_temp_dir();
      $this->assertFalse($rule->validate($test) instanceof ValidationError);
      $test = sys_get_temp_dir().DIRECTORY_SEPARATOR.'newdir';
      $this->assertFalse($rule->validate($test) instanceof ValidationError);
      $test = __DIR__.'/some/new/dir';
      $this->assertFalse($rule->validate($test) instanceof ValidationError);

      # failure
      $this->assertTrue($rule->validate('/___nope___') instanceof ValidationError);
   }

   public function testFile()
   {
      $rule = new WritablePath();

      # success
      $test = sys_get_temp_dir().DIRECTORY_SEPARATOR.'file.txt';
      $this->assertFalse($rule->validate($test) instanceof ValidationError);
      $test = __DIR__.'/some/new/file.txt';
      $this->assertFalse($rule->validate($test) instanceof ValidationError);

      # failure
      $test = '/___nope___/file.txt';
      $this->assertTrue($rule->validate($test) instanceof ValidationError);
   }

   /**
    * @expectedException InvalidArgumentException
    */
   public function testInvalidArgException()
   {
      new WritablePath('hello');
   }
}

