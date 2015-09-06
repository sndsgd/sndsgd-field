<?php

namespace sndsgd\field;


/**
 * @coversDefaultClass \sndsgd\field\LatitudeField
 */
class LatitudeFieldTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->field = new LatitudeField("latitude");
    }

    /**
     * @covers ::setDefault
     * @covers ::getDefault
     */
    public function testSetDefault()
    {
        $this->field->setDefault(42.4242);
        $this->assertEquals(42.4242, $this->field->getDefault());
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testSetDefaultInvalidTypeException()
    {
        $this->field->setDefault("string");
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testSetDefaultOutOfRangeException()
    {
        $this->field->setDefault(1000);
    }
}
