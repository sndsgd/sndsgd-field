<?php

namespace sndsgd\field\rule;

use \sndsgd\Field;
use \sndsgd\field\StringField;
use \sndsgd\field\Error;


class ClosureRuleTest extends \PHPUnit_Framework_TestCase
{
    public static function stringValidatorTest($v, $d, $n, $i, $c)
    {
        return $v;
    }

    public function setUp()
    {
        $this->rule = new ClosureRule(function($v, $i=null, $f=null, $c=null) {
            if (!is_int($v)) {
                return false;
            }
            $newValue = ($v % 2 === 0) ? 'even' : 'odd';
            return [true, $newValue];
        });
    }

    public function testSuccess()
    {
        $this->rule->setValue(10);
        $this->assertTrue($this->rule->validate());
        $this->assertEquals('even', $this->rule->getValue());

        $this->rule->setValue(2);
        $this->assertTrue($this->rule->validate());
        $this->assertEquals('even', $this->rule->getValue());

        $this->rule->setValue(3);
        $this->assertTrue($this->rule->validate());
        $this->assertEquals('odd', $this->rule->getValue());

        $this->rule->setValue(7);
        $this->assertTrue($this->rule->validate());
        $this->assertEquals('odd', $this->rule->getValue());
    }

    public function testFailure()
    {
        $this->rule->setValue('abc');
        $this->assertFalse($this->rule->validate());

        $this->rule->setValue([]);
        $this->assertFalse($this->rule->validate());

        $this->rule->setValue('10');
        $this->assertFalse($this->rule->validate());
    }

    public function testGetClass()
    {
        $res = $this->rule->getClass();
        $this->assertTrue(is_string($res));

        $r = new ClosureRule(__CLASS__.'::stringValidatorTest');
        $this->assertTrue(is_string($r->getClass()));
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testConstructorNotCallableException()
    {
        new ClosureRule([]);
    }

    public function testAddMultipleClosures()
    {
        $field = (new StringField('test'))
            ->addRules([
                $this->rule,
                new ClosureRule(function($v, $i, $f, $c) {
                    return $v;
                }),
                new ClosureRule(function($v, $i, $f, $c) {
                    return $v;
                }),
                new ClosureRule(function($v, $i, $f, $c) {
                    return $v;
                }),
                new ClosureRule(function($v, $i, $f, $c) {
                    return $v;
                })
            ]);
    }

    public function testClosureScope()
    {
        // validation always succeeds
        // the value should be updated to the classname as string
        $rule = new ClosureRule(function($v, $i=null, $f=null, $c=null) {
            return [true, get_class($this)];
        });
        $rule->validate();
        $this->assertEquals(get_class($rule), $rule->getValue());

        // validation always fails
        // set the rule's message within the closure
        $rule = new ClosureRule(function($v, $i=null, $f=null, $c=null) {
            $this->setMessage("fail");
            return false;
        });
        $rule->validate();
        $this->assertEquals("fail", $rule->getError()->getMessage());
    }
}
