<?php

namespace sndsgd\field\rule;

use \sndsgd\Field;
use \sndsgd\field\Collection;
use \sndsgd\field\IntegerField;


class MinValueCountRuleTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException InvalidArgumentException
     */
    public function testNullMaxException()
    {
        new MinValueCountRule();
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testBadMaxException()
    {
        new MinValueCountRule('hello');
    }

    /**
     * @expectedException Exception
     */
    public function testValidateNoFieldException()
    {
        $rule = new MinValueCountRule(2);
        $rule->validate();
    }

    public function testGetMessage()
    {
        $rule = new MinValueCountRule(10);
        $rule->setMessage('{{len}} {{what}}');
        $error = $rule->getError();
        $this->assertEquals('10 values', $error->getMessage());
    }

    public function test()
    {
        $coll = new Collection;
        $field = new IntegerField('test');
        $rule = new MinValueCountRule(2);
        $field->addRule($rule);
        $coll->addField($field);

        # failure
        $field->addValue(1);
        $rule->setField($field, 0);
        $rule->setCollection($coll);
        $this->assertFalse($rule->validate());

        # success
        $field->addValue(2);
        $rule->setField($field, 1);
        $rule->setCollection($coll);
        $this->assertTrue($rule->validate());

        # success
        $field->addValue(3);
        $rule->setField($field, 2);
        $rule->setCollection($coll);
        $this->assertTrue($rule->validate());
    }
}
