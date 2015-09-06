<?php

namespace sndsgd\field\rule;


class AlphaNumRuleTest extends \PHPUnit_Framework_TestCase
{
    public function testSuccess()
    {
        $rule = new AlphaNumRule;

        $rule->setValue('abcdefghijklmno');
        $this->assertTrue($rule->validate());

        $rule->setValue('0');
        $this->assertTrue($rule->validate());
    }

    public function testFailure()
    {
        $rule = new AlphaNumRule;

        $rule->setValue('asd-asd');
        $this->assertFalse($rule->validate());
    }
}
