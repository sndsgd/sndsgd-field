<?php

namespace sndsgd\field\rule;

use \sndsgd\field\Collection;
use \sndsgd\field\Field;
use \sndsgd\field\ValidationError;


class MinValueCountTest extends \PHPUnit_Framework_TestCase
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
      $this->assertTrue($result instanceof ValidationError);

      # 2 values
      $field->addValue(1);
      $result = $rule->validate($field->getValue(0), 'test', 0, $coll);
      $this->assertFalse($result instanceof ValidationError);      
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
