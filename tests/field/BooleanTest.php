<?php

namespace sndsgd\field;


class BooleanTest extends \PHPUnit_Framework_TestCase
{
   public function testConstructor()
   {
      $field = Boolean::create('name');
      $this->assertEquals('name', $field->getName());
   }

   public function testSetDefault()
   {
      $field = Boolean::create('name');
      $field->setDefault(true);
      $this->assertTrue($field->getDefault());
   }

   /**
    * @expectedException InvalidArgumentException
    */
   public function testSetDefaultException()
   {
      $field = Boolean::create('name');
      $field->setDefault('string');
   }
}

