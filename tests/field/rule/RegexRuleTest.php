<?php

namespace sndsgd\field\rule;


class RegexRuleTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException InvalidArgumentException
     */
    public function testNullRegexException()
    {
        new RegexRule();
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testBadRegexException()
    {
        new RegexRule('/[a-z]');
    }

    public function test()
    {
        $rule = new RegexRule('/[A-Z][a-z]+/');

        # success
        $rule->setValue('Hello');
        $this->assertTrue($rule->validate());

        # failure
        $rule->setValue('hello');
        $this->assertFalse($rule->validate());
    }   
}
