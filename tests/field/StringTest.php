<?php

namespace sndsgd\field;


class StringTest extends \PHPUnit_Framework_TestCase
{
   public function testConstructor()
   {
      $field = String::create('name');
      $this->assertEquals('name', $field->getName());
   }

   public function testSetDefault()
   {
      $field = String::create('name');
      $field->setDefault('Russell');
      $this->assertEquals('Russell', $field->getDefault());
   }

   /**
    * @expectedException InvalidArgumentException
    */
   public function testSetDefaultException()
   {
      $field = String::create('name');
      $field->setDefault(0);
   }
}

