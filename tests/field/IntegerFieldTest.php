<?php

namespace sndsgd\field;


/**
 * @coversDefaultClass \sndsgd\field\IntegerField
 */
class IntegerFieldTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->field = new IntegerField('name');
    }

    /**
     * @covers ::setDefault
     * @covers ::getDefault
     */
    public function testSetDefault()
    {
        $this->field->setDefault(42);
        $this->assertEquals(42, $this->field->getDefault());
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testSetDefaultException()
    {
        $this->field->setDefault('string');
    }
}
