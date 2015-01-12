<?php

namespace sndsgd\field\rule;


class WritablePathTest extends \sndsgd\field\RuleTestCase
{
   public function testDir()
   {
      $rule = new WritablePath(true);

      # success
      $test = sys_get_temp_dir();
      $this->assertValid($rule->validate($test));
      $test = sys_get_temp_dir().DIRECTORY_SEPARATOR.'newdir';
      $this->assertValid($rule->validate($test));
      $test = __DIR__.'/some/new/dir';
      $this->assertValid($rule->validate($test));

      # failure
      $this->assertValidationError($rule->validate('/___nope___'));
   }

   public function testFile()
   {
      $rule = new WritablePath();

      # success
      $test = sys_get_temp_dir().DIRECTORY_SEPARATOR.'file.txt';
      $this->assertValid($rule->validate($test));
      $test = __DIR__.'/some/new/file.txt';
      $this->assertValid($rule->validate($test));

      # failure
      $test = '/___nope___/file.txt';
      $this->assertValidationError($rule->validate($test));
   }

   /**
    * @expectedException InvalidArgumentException
    */
   public function testInvalidArgException()
   {
      new WritablePath('hello');
   }
}

