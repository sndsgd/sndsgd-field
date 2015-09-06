<?php

namespace sndsgd\field\rule;

use \StdClass;


class NumberRuleTest extends \PHPUnit_Framework_TestCase
{
    public function test()
    {
        $rule = new NumberRule;

        $rule->setValue(42);
        $this->assertTrue($rule->validate());
        $this->assertEquals(42, $rule->getValue());

        $rule->setValue('42');
        $this->assertTrue($rule->validate());
        $this->assertEquals(42, $rule->getValue());

        $rule->setValue(-42);
        $this->assertTrue($rule->validate());
        $this->assertEquals(-42, $rule->getValue());

        $rule->setValue('-42');
        $this->assertTrue($rule->validate());
        $this->assertEquals(-42, $rule->getValue());

        $rule->setValue(4.2);
        $this->assertTrue($rule->validate());
        $this->assertEquals(4.2, $rule->getValue());

        $rule->setValue('4.2');
        $this->assertTrue($rule->validate());
        $this->assertEquals(4.2, $rule->getValue());

        $rule->setValue('-4.2');
        $this->assertTrue($rule->validate());
        $this->assertEquals(-4.2, $rule->getValue());
    }

    public function testFailure()
    {
        $rule = new NumberRule;

        $rule->setValue('abc');
        $this->assertFalse($rule->validate());

        $rule->setValue('a42');
        $this->assertFalse($rule->validate());

        $rule->setValue('');
        $this->assertFalse($rule->validate());

        $rule->setValue(' ');
        $this->assertFalse($rule->validate());

        $rule->setValue(true);
        $this->assertFalse($rule->validate());

        $rule->setValue(false);
        $this->assertFalse($rule->validate());

        $rule->setValue([]);
        $this->assertFalse($rule->validate());

        $rule->setValue(new StdClass);
        $this->assertFalse($rule->validate());
    }
}
