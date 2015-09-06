<?php

namespace sndsgd\field\rule;

use \DateTime;
use \DateTimeZone;
use \StdClass;


class DateStringRuleTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException InvalidArgumentException
     */
    public function testBadFormatException()
    {
        new DateStringRule(42);
    }

    public function testSetTimezone()
    {
        $rule = new DateStringRule;
        $dtz = new DateTimeZone('Europe/London');
        $rule->setTimezone($dtz);
    }

    public function testSuccess()
    {
        $rule = new DateStringRule;

        $test = 'October 14th, 2014';
        $fmt = 'F jS, Y';
        $rule->setValue($test);
        $this->assertTrue($rule->validate());
        $value = $rule->getValue();
        $this->assertInstanceOf('DateTime', $value);
        $this->assertEquals($test, $value->format($fmt));

        $test = '2008-08-07 18:11:31';
        $fmt = 'Y-m-d H:i:s';
        $rule->setValue($test);
        $this->assertTrue($rule->validate());
        $value = $rule->getValue();
        $this->assertInstanceOf('DateTime', $value);
        $this->assertEquals($test, $value->format($fmt));
    }

    public function testSetFormat()
    {
        $test = '2015-01-01 06:08:10';
        $fmt = 'Y-m-d H:i:s';
        $rule = new DateStringRule($fmt);
        $rule->setValue($test);
        $this->assertTrue($rule->validate());
        $value = $rule->getValue();
        $this->assertInstanceOf('DateTime', $value);
        $this->assertEquals($test, $value->format($fmt));
    }

    public function testFailure()
    {
        $rule = new DateStringRule;

        $rule->setValue(42);
        $this->assertFalse($rule->validate());

        $rule->setValue('42');
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

        # test failure to create DateTime from format
        $rule = new DateStringRule('asd');
        $rule->setValue('42');
        $this->assertFalse($rule->validate());
    }
}
