<?php

namespace sndsgd\field;


class IntegerFieldTest extends \PHPUnit_Framework_TestCase
{
   public function testConstructor()
   {
      $field = IntegerField::create('name');
      $this->assertEquals('name', $field->getName());
   }

   public function testSetDefault()
   {
      $field = IntegerField::create('name');
      $field->setDefault(42);
      $this->assertEquals(42, $field->getDefault());
   }

   /**
    * @expectedException InvalidArgumentException
    */
   public function testSetDefaultException()
   {
      $field = IntegerField::create('name');
      $field->setDefault('string');
   }
}

