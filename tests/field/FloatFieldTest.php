<?php

namespace sndsgd\field;


class FloatFieldTest extends \PHPUnit_Framework_TestCase
{
   public function testConstructor()
   {
      $field = FloatField::create('name');
      $this->assertEquals('name', $field->getName());
   }

   public function testSetDefault()
   {
      $field = FloatField::create('name');
      $field->setDefault(4.2);
      $this->assertEquals(4.2, $field->getDefault());
   }

   /**
    * @expectedException InvalidArgumentException
    */
   public function testSetDefaultException()
   {
      $field = FloatField::create('name');
      $field->setDefault('string');
   }
}

