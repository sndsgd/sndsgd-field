<?php

namespace sndsgd\field;


/**
 * @coversDefaultClass \sndsgd\field\String
 */
class StringTest extends \PHPUnit_Framework_TestCase
{
   public function setUp()
   {
      $this->field = new String('name');
   }

   /**
    * @covers ::setDefault
    * @covers ::getDefault
    */
   public function testSetDefault()
   {
      $this->field->setDefault('Russell');
      $this->assertEquals('Russell', $this->field->getDefault());
   }

   /**
    * @expectedException InvalidArgumentException
    */
   public function testSetDefaultException()
   {
      $this->field->setDefault(0);
   }
}

