<?php

namespace sndsgd\field;

use \sndsgd\Field;


/**
 * @coversDefaultClass \sndsgd\field\Error
 */
class ErrorTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->message = 'validation failed';
        $this->error = new Error($this->message);
    }

    /**
     * @covers ::__construct
     * @covers ::getMessage
     */
    public function testGetMessage()
    {
        $this->assertEquals($this->message, $this->error->getMessage());
    }

    /**
     * @covers ::setName
     * @covers ::getName
     */
    public function testSetGetName()
    {
        $name = 'test';
        $this->error->setName($name);
        $this->assertEquals($name, $this->error->getName());
    }

    /**
     * @covers ::setIndex
     * @covers ::getIndex
     */
    public function testSetGetIndex()
    {
        $index = 1;
        $this->error->setIndex($index);
        $this->assertEquals($index, $this->error->getIndex());
    }

    /**
     * @covers ::setValue
     * @covers ::getValue
     */
    public function testSetGetValue()
    {
        # 150 chars
        $value = "lqAiYeG6svTDReOhIwI6O0DVPqvlTpWlR40eKfl5NDF72Bo63nhdKDPtDq1RfaelhTxF8tRzthdiOuSl9QSDpABNjy6G3gS18pBSsfBkWUduYh0PnmidgqNLNfjM3itEXDf3QiL7AAjmQyJr1SUsrd";
        # 96 chars + ...
        $expect = "lqAiYeG6svTDReOhIwI6O0DVPqvlTpWlR40eKfl5NDF72Bo63nhdKDPtDq1RfaelhTxF8tRzthdiOuSl9QSDpABNjy6G3gS1...";
        $this->error->setValue($value);
        $this->assertEquals($expect, $this->error->getValue());
    }

    public function testExport()
    {
        $this->error->setName("test");
        $this->error->setValue(42);
        $this->error->setIndex(0);
        $expect = [
            "name" => "test",
            "index" => 0,
            "message" => "validation failed",
            "value" => 42
        ];
        $this->assertEquals($expect, $this->error->export());
    }
}
