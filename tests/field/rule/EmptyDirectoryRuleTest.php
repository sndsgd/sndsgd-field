<?php

namespace sndsgd\field\rule;

use \StdClass;
use \org\bovigo\vfs\vfsStream;


class EmptyDirectoryRuleTest extends \PHPUnit_Framework_TestCase
{
   public function setUp()
   {
      $this->root = vfsStream::setup('root');
      vfsStream::create([
         'empty-dir' => [],
         'one-child' => [
            'file.txt' => 'contents',
         ],
         'multiple-children' => [
            'file.txt' => 'contents',
            'file2.txt' => 'contents',
         ],
      ]);
   }

   public function testSuccess()
   {
      $rule = new EmptyDirectoryRule;

      $url = vfsStream::url('root/empty-dir');
      $rule->setValue($url);
      $this->assertTrue($rule->validate());
      $this->assertEquals($url, $rule->getValue());
   }

   public function testFailure()
   {
      $rule = new EmptyDirectoryRule;

      $url = vfsStream::url('root/one-child');
      $rule->setValue($url);
      $this->assertFalse($rule->validate());
      $msg = "'$url' must be an empty directory";
      $this->assertEquals($msg, $rule->getMessage());
      
      $url = vfsStream::url('root/multiple-children');
      $rule->setValue($url);
      $this->assertFalse($rule->validate());
      $msg = "'$url' must be an empty directory";
      $this->assertEquals($msg, $rule->getMessage());
   }
}

