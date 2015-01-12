<?php

namespace sndsgd\field\rule;

use \sndsgd\Field;
use \sndsgd\field\Collection;


class MinValueCountTest extends \sndsgd\field\RuleTestCase
{
   public function test()
   {
      $coll = new Collection;
      $field = Field::int('test');
      $coll->addFields($field);
      $rule = new MinValueCount(2);

      # 1 value
      $field->addValue(1);
      $result = $rule->validate($field->getValue(0), 'test', 0, $coll);
      $this->assertValidationError($result);

      # 2 values
      $field->addValue(1);
      $result = $rule->validate($field->getValue(0), 'test', 0, $coll);
      $this->assertValid($result);
   }

   /**
    * @expectedException InvalidArgumentException
    */
   public function testNullMaxException()
   {
      new MinValueCount();
   }

   /**
    * @expectedException InvalidArgumentException
    */
   public function testBadMaxException()
   {
      new MinValueCount('hello');
   }
}

