<?php

namespace sndsgd\field\rule;

use \sndsgd\Field;
use \sndsgd\field\Collection;
use \sndsgd\field\IntegerField;


class MaxValueCountRuleTest extends \PHPUnit_Framework_TestCase
{
   /**
    * @expectedException InvalidArgumentException
    */
   public function testNullMaxException()
   {
      new MaxValueCountRule();
   }

   /**
    * @expectedException InvalidArgumentException
    */
   public function testBadMaxException()
   {
      new MaxValueCountRule('hello');
   }

   /**
    * @expectedException Exception
    */
   public function testValidateNoFieldException()
   {
      $rule = new MaxValueCountRule(2);
      $rule->validate();
   }

   public function testGetMessage()
   {
      $rule = new MaxValueCountRule(10);
      $rule->setMessage('{{len}} {{what}}');
      $error = $rule->getError();
      $this->assertEquals('10 values', $error->getMessage());
   }

   public function testSuccess()
   {
      $coll = new Collection;
      $field = new IntegerField('test');
      $rule = new MaxValueCountRule(2);
      $field->addRule($rule);
      $coll->addField($field);

      $field->addValue(1);
      $rule->setField($field, 0);
      $rule->setCollection($coll);
      $this->assertTrue($rule->validate());

      $field->addValue(2);
      $rule->setField($field, 1);
      $rule->setCollection($coll);
      $this->assertTrue($rule->validate());

      # fails
      $field->addValue(3);
      $rule->setField($field, 2);
      $rule->setCollection($coll);
      $this->assertFalse($rule->validate());
   }
}

