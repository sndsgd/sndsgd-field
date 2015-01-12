<?php

namespace sndsgd\field;


class IntegerTest extends \PHPUnit_Framework_TestCase
{
   public function testConstructor()
   {
      $field = Integer::create('name');
      $this->assertEquals('name', $field->getName());
   }

   public function testSetDefault()
   {
      $field = Integer::create('name');
      $field->setDefault(42);
      $this->assertEquals(42, $field->getDefault());
   }

   /**
    * @expectedException InvalidArgumentException
    */
   public function testSetDefaultException()
   {
      $field = Integer::create('name');
      $field->setDefault('string');
   }
}

