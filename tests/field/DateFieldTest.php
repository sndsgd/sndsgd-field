<?php

namespace sndsgd\field;

use \DateTime;

/**
 * @coversDefaultClass \sndsgd\field\DateField
 */
class DateFieldTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->field = new DateField('name');
    }

    /**
     * @covers ::setDefault
     * @covers ::getDefault
     */
    public function testSetDefault()
    {
        $this->field->setDefault(new DateTime('now'));
        $this->assertInstanceOf('DateTime', $this->field->getDefault());
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testSetDefaultException()
    {
        $this->field->setDefault('string');
    }
}
