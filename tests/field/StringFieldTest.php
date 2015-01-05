<?php

namespace sndsgd\field;


class StringFieldTest extends \PHPUnit_Framework_TestCase
{
   public function testConstructor()
   {
      $field = StringField::create('name');
      $this->assertEquals('name', $field->getName());
   }

   public function testSetDefault()
   {
      $field = StringField::create('name');
      $field->setDefault('Russell');
      $this->assertEquals('Russell', $field->getDefault());
   }

   /**
    * @expectedException InvalidArgumentException
    */
   public function testSetDefaultException()
   {
      $field = StringField::create('name');
      $field->setDefault(0);
   }
}

