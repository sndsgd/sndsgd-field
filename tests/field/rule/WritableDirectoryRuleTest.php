<?php

namespace sndsgd\field\rule;

use \StdClass;
use \org\bovigo\vfs\vfsStream;


class WritableDirectoryRuleTest extends \PHPUnit_Framework_TestCase
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
            'not-writable' => [
                'file.txt' => 'contents'
            ],
            'empty-not-writable' => [],
        ]);

        foreach (['not-writable', 'empty-not-writable'] as $dir) {
            $this->root->getChild($dir)
                ->chmod(0700)
                ->chown(vfsStream::OWNER_ROOT)
                ->chgrp(vfsStream::GROUP_ROOT);
        }
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testConstructorInvalidArg()
    {
        $rule = new WritableDirectoryRule(42);
    }

    public function testSuccess()
    {
        $rule = new WritableDirectoryRule;

        $url = vfsStream::url('root/empty-dir');
        $rule->setValue($url);
        $this->assertTrue($rule->validate());

        $url = vfsStream::url('root/one-child');
        $rule->setValue($url);
        $this->assertTrue($rule->validate());
    }

    public function testFailure()
    {
        $rule = new WritableDirectoryRule;

        $url = vfsStream::url('root/empty-not-writable');
        $rule->setValue($url);
        $this->assertFalse($rule->validate());

        $url = vfsStream::url('root/empty-not-writable');
        $rule->setValue($url);
        $this->assertFalse($rule->validate());


        $rule = new WritableDirectoryRule(true);

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
