<?php

namespace sndsgd\field\rule;

use \StdClass;


class FloatRuleTest extends \PHPUnit_Framework_TestCase
{
    public function testSuccess()
    {
        $rule = new FloatRule;

        $rule->setValue(42);
        $this->assertTrue($rule->validate());
        $this->assertSame(42.0, $rule->getValue());

        $rule->setValue('42');
        $this->assertTrue($rule->validate());
        $this->assertSame(42.0, $rule->getValue());

        $rule->setValue(-42);
        $this->assertTrue($rule->validate());
        $this->assertSame(-42.0, $rule->getValue());

        $rule->setValue('-42');
        $this->assertTrue($rule->validate());
        $this->assertSame(-42.0, $rule->getValue());

        $rule->setValue(4.2);
        $this->assertTrue($rule->validate());
        $this->assertSame(4.2, $rule->getValue());

        $rule->setValue('4.2');
        $this->assertTrue($rule->validate());
        $this->assertSame(4.2, $rule->getValue());

        $rule->setValue('-4.2');
        $this->assertTrue($rule->validate());
        $this->assertSame(-4.2, $rule->getValue());
    }

    public function testFailure()
    {
        $rule = new FloatRule;

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
