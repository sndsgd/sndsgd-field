<?php

namespace sndsgd\field;


class BooleanFieldTest extends \PHPUnit_Framework_TestCase
{
   public function testConstructor()
   {
      $field = BooleanField::create('name');
      $this->assertEquals('name', $field->getName());
   }

   public function testSetDefault()
   {
      $field = BooleanField::create('name');
      $field->setDefault(true);
      $this->assertTrue($field->getDefault());
   }

   /**
    * @expectedException InvalidArgumentException
    */
   public function testSetDefaultException()
   {
      $field = BooleanField::create('name');
      $field->setDefault('string');
   }
}

