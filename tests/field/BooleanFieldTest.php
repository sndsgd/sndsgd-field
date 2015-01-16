<?php

namespace sndsgd\field;


/**
 * @coversDefaultClass \sndsgd\field\BooleanField
 */
class BooleanFieldTest extends \PHPUnit_Framework_TestCase
{
   public function setUp()
   {
      $this->field = new BooleanField('name');
   }

   /**
    * @covers ::setDefault
    * @covers ::getDefault
    */
   public function testSetDefault()
   {
      $this->field->setDefault(true);
      $this->assertTrue($this->field->getDefault());
   }

   /**
    * @expectedException InvalidArgumentException
    */
   public function testSetDefaultException()
   {
      $this->field->setDefault('string');
   }
}

