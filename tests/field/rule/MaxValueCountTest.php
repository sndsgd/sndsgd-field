<?php

namespace sndsgd\field\rule;

use \sndsgd\Field;
use \sndsgd\field\Collection;
use \sndsgd\field\ValidationError;


class MaxValueCountTest extends RuleTestCase
{
   public function test()
   {
      $coll = new Collection;
      $field = Field::int('test');
      $coll->addFields($field);
      $rule = new MaxValueCount(1);

      # 1 value
      $field->addValue(1);
      $result = $rule->validate($field->getValue(0), 'test', 0, $coll);
      $this->assertValid($result);

      # 2 values
      $field->addValue(1);
      $result = $rule->validate($field->getValue(0), 'test', 0, $coll);
      $this->assertValidationError($result);
   }

   /**
    * @expectedException InvalidArgumentException
    */
   public function testNullMaxException()
   {
      new MaxValueCount();
   }

   /**
    * @expectedException InvalidArgumentException
    */
   public function testBadMaxException()
   {
      new MaxValueCount('hello');
   }
}

