<?php

namespace sndsgd\field\rule;

use \sndsgd\Dir;
use \sndsgd\File;


class PathTestRuleTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException InvalidArgumentException
     */
    public function testNullBitmaskException()
    {
        new PathTestRule();
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidArgException()
    {
        new PathTestRule('hello');
    }

    public function testDir()
    {
        $rule = new PathTestRule(Dir::WRITABLE);

        $rule->setValue(sys_get_temp_dir());
        $this->assertTrue($rule->validate());

        $rule->setValue('/___nope___');
        $this->assertFalse($rule->validate());
    }

    public function testFile()
    {
        $rule = new PathTestRule(File::READABLE);

        $rule->setValue(__FILE__);
        $this->assertTrue($rule->validate());

        $rule->setValue(__DIR__);
        $this->assertFalse($rule->validate());
    }
}
