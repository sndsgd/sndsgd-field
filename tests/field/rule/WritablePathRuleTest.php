<?php

namespace sndsgd\field\rule;


class WritablePathRuleTest extends \PHPUnit_Framework_TestCase
{
   public function testDir()
   {
      $rule = new WritablePathRule(true);

      # success
      $rule->setValue(sys_get_temp_dir());
      $this->assertTrue($rule->validate());

      $rule->setValue(sys_get_temp_dir().DIRECTORY_SEPARATOR.'newdir');
      $this->assertTrue($rule->validate());

      $rule->setValue(__DIR__.'/some/new/dir');
      $this->assertTrue($rule->validate());

      # failure
      $rule->setValue('/___nope___');
      $this->assertFalse($rule->validate());
   }

   public function testFile()
   {
      $rule = new WritablePathRule();

      # success
      $rule->setValue(sys_get_temp_dir().DIRECTORY_SEPARATOR.'file.txt');
      $this->assertTrue($rule->validate());

      # success
      $rule->setValue(__DIR__.'/some/new/file.txt');
      $this->assertTrue($rule->validate());

      # failure
      $rule->setValue('/___nope___/file.txt');
      $this->assertFalse($rule->validate());
   }

   /**
    * @expectedException InvalidArgumentException
    */
   public function testInvalidArgException()
   {
      new WritablePathRule('hello');
   }
}

