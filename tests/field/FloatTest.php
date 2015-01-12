<?php

namespace sndsgd\field;


class FloatTest extends \PHPUnit_Framework_TestCase
{
   public function testConstructor()
   {
      $field = Float::create('name');
      $this->assertEquals('name', $field->getName());
   }

   public function testSetDefault()
   {
      $field = Float::create('name');
      $field->setDefault(4.2);
      $this->assertEquals(4.2, $field->getDefault());
   }

   /**
    * @expectedException InvalidArgumentException
    */
   public function testSetDefaultException()
   {
      $field = Float::create('name');
      $field->setDefault('string');
   }
}

