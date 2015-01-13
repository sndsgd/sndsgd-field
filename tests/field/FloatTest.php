<?php

namespace sndsgd\field;


/**
 * @coversDefaultClass \sndsgd\field\Float
 */
class FloatTest extends \PHPUnit_Framework_TestCase
{
   public function setUp()
   {
      $this->field = new Float('name');
   }

   /**
    * @covers ::setDefault
    * @covers ::getDefault
    */
   public function testSetDefault()
   {
      $this->field->setDefault(4.2);
      $this->assertEquals(4.2, $this->field->getDefault());
   }

   /**
    * @expectedException InvalidArgumentException
    */
   public function testSetDefaultException()
   {
      $this->field->setDefault('string');
   }
}

