<?php

namespace sndsgd\field\rule;


class RegexStringRuleTest extends \PHPUnit_Framework_TestCase
{
    public function testSuccess()
    {
        $rule = new RegexStringRule;

        $rule->setValue('~\.php$~i');
        $this->assertTrue($rule->validate());
        $regex = $rule->getValue();
        $this->assertEquals(1, preg_match($regex, 'some/path/file.php'));
    }

    public function testFailure()
    {
        $rule = new RegexStringRule;
        $rule->setValue('~\.php$');
        $this->assertFalse($rule->validate());
        $error = $rule->getError();
        $expect = "invalid regex: no ending delimiter '~' found";
        $this->assertEquals($expect, $error->getMessage());
    }   
}
