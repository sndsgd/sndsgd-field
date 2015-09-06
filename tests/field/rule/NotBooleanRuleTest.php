<?php

namespace sndsgd\field\rule;


class NotBooleanRuleTest extends \PHPUnit_Framework_TestCase
{
    public function testSuccess()
    {
        $rule = new NotBooleanRule;

        $rule->setValue('true');
        $this->assertTrue($rule->validate());
        $this->assertEquals('true', $rule->getValue());

        $rule->setValue('TRUE');
        $this->assertTrue($rule->validate());
        $this->assertEquals('TRUE', $rule->getValue());

        $rule->setValue('false');
        $this->assertTrue($rule->validate());
        $this->assertEquals('false', $rule->getValue());

        $rule->setValue('FALSE');
        $this->assertTrue($rule->validate());
        $this->assertEquals('FALSE', $rule->getValue());

        $rule->setValue('on');
        $this->assertTrue($rule->validate());
        $this->assertEquals('on', $rule->getValue());

        $rule->setValue('off');
        $this->assertTrue($rule->validate());
        $this->assertEquals('off', $rule->getValue());

        $rule->setValue('1');
        $this->assertTrue($rule->validate());
        $this->assertEquals('1', $rule->getValue());

        $rule->setValue('0');
        $this->assertTrue($rule->validate());
        $this->assertEquals('0', $rule->getValue());

        $rule->setValue(1);
        $this->assertTrue($rule->validate());
        $this->assertEquals(1, $rule->getValue());

        $rule->setValue(0);
        $this->assertTrue($rule->validate());
        $this->assertEquals(0, $rule->getValue());

        $rule->setValue([]);
        $this->assertTrue($rule->validate());
        $this->assertEquals([], $rule->getValue());
    }

    public function testFailure()
    {
        $rule = new NotBooleanRule;

        $rule->setValue(true);
        $this->assertFalse($rule->validate());

        $rule->setValue(false);
        $this->assertFalse($rule->validate());
    }
}
