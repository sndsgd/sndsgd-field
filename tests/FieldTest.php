<?php

namespace sndsgd;

use \sndsgd\Event;
use \sndsgd\field\String;
use \sndsgd\field\Collection;
use \sndsgd\field\rule\Required as RequiredRule;
use \sndsgd\field\rule\MinValue as MinValueRule;
use \sndsgd\field\rule\MaxValue as MaxValueRule;


/**
 * @coversDefaultClass \sndsgd\Field
 */
class FieldTest extends \PHPUnit_Framework_TestCase
{
   /**
    * @coversNothing
    */
   public function setUp()
   {
      $this->field = Field::str('test');
   }

   /**
    * @covers ::__callStatic
    */
   public function testCallStatic()
   {
      $field = Field::bool('name');
      $this->assertInstanceOf('sndsgd\\field\\Boolean', $field);
      $field = Field::boolean('name', 'n');
      $this->assertInstanceOf('sndsgd\\field\\Boolean', $field);
      $this->assertEquals(['n'], $field->getAliases());

      $field = Field::int('name');
      $this->assertInstanceOf('sndsgd\\field\\Integer', $field);
      $field = Field::integer('name');
      $this->assertInstanceOf('sndsgd\\field\\Integer', $field);

      $field = Field::flt('name');
      $this->assertInstanceOf('sndsgd\\field\\Float', $field);
      $field = Field::float('name');
      $this->assertInstanceOf('sndsgd\\field\\Float', $field);

      $field = Field::str('name');
      $this->assertInstanceOf('sndsgd\\field\\String', $field);
      $field = Field::string('name');
      $this->assertInstanceOf('sndsgd\\field\\String', $field);
   }

   /**
    * @covers ::__callStatic
    * @expectedException InvalidArgumentException
    */
   public function testCallStaticTypeException()
   {
      Field::blegh('jimbo');
   }

   /**
    * @covers ::__callStatic
    * @expectedException InvalidArgumentException
    */
   public function testCallStaticNameException()
   {
      Field::int([]);
   }

   public function testFieldEvents()
   {
      $result = null;
      $this->field->on('parse', function(Event $ev) use (&$result) {
         $result = $ev->getData('name');
      });

      $this->field->fire('parse', [
         'collection' => null,
         'field' => $this->field,
         'name' => 'test'
      ]);

      $this->assertEquals($result, 'test');
   }

   /**
    * @covers ::setName
    * @expectedException InvalidArgumentException
    */
   public function testSetNameException()
   {
      new String(42);
   }

   /**
    * @covers ::setDescription
    * @covers ::getDescription
    */
   public function testSetGetDescription()
   {
      $desc = 'just a test description';
      $this->field->setDescription($desc);
      $this->assertEquals($desc, $this->field->getDescription());
   }

   /**
    * @covers ::setDescription
    * @expectedException InvalidArgumentException
    */
   public function testSetDescriptionException()
   {
      $this->field->setDescription(42);
   }

   /**
    * @covers ::setExportHandler
    * @covers ::exportValue
    */
   public function testSetExportNormal()
   {
      $this->field->addValue(42);
      $this->field->setExportHandler(Field::EXPORT_NORMAL);
      $this->assertEquals(42, $this->field->exportValue());
      $this->field->addValue(84);
      $this->assertEquals([42, 84], $this->field->exportValue());
   }

   /**
    * @covers ::setExportHandler
    * @covers ::exportValue
    */
   public function testSetExportArray()
   {
      $this->field->addValue(42);
      $this->field->setExportHandler(Field::EXPORT_ARRAY);
      $this->assertEquals([42], $this->field->exportValue());
      $this->field->addValue(84);
      $this->assertEquals([42, 84], $this->field->exportValue());
   }

   /**
    * @covers ::setExportHandler
    * @expectedException InvalidArgumentException
    */
   public function testSetExportHandlerException()
   {
      $this->field->setExportHandler([]);
   }

   /**
    * @covers ::addValue
    * @covers ::setExportHandler
    * @covers ::exportValue
    */
   public function testExportClosure()
   {
      $this->field->addValue(42);
      $this->field->setExportHandler(function(array $values) {
         $ret = [];
         foreach ($values as $value) {
            $ret[] = $value + 1;
         }
         return $ret;
      });
      $this->assertEquals([43], $this->field->exportValue());

      $this->field->addValue(10);
      $this->assertEquals([43,11], $this->field->exportValue());
   }

   /**
    * @covers ::hasValue
    */
   public function testHasValue()
   {
      $this->assertFalse($this->field->hasValue());
      $this->field->addValue(10);
      $this->assertTrue($this->field->hasValue());
   }

   /**
    * @covers ::setValue
    * @covers ::getValue
    */
   public function testGetValue()
   {
      $val = 'default-value';
      $this->field->setDefault($val);
      $this->assertEquals($val, $this->field->getValue());
   }

   /**
    * @covers ::setValue
    * @covers ::exportValue
    */
   public function testSetValue()
   {
      $this->field->setValue(42);
      $this->assertEquals(42, $this->field->exportValue());

      $val = ['one', 'two', 'three'];
      $this->field->setValue($val);
      $this->assertEquals($val, $this->field->exportValue());

      $this->field->setValue(2, 1);
      $this->assertEquals(['one', 2, 'three'], $this->field->exportValue());      
   }

   /**
    * @covers ::addRules
    * @expectedException InvalidArgumentException
    */
   public function testAddRulesNotRuleException()
   {
      $this->field->addRules(42);
   }

   /**
    * @covers ::addRules
    * @expectedException Exception
    */
   public function testAddRulesDupeRuleException()
   {
      $this->field->addRules(
         new RequiredRule,
         new RequiredRule
      );
   }

   /**
    * @covers ::addRules
    * @covers ::validate
    */
   public function testValidateNotRequired()
   {
      $coll = new Collection();
      $coll->addFields($this->field);
      $this->field->addRules(
         new MinValueRule(1),
         new MaxValueRule(10)
      );

      // no value, but not required (valid)
      $this->assertEquals(0, $this->field->validate());
   }

   /**
    * @covers ::addRules
    * @covers ::getRules
    */
   public function testGetRules()
   {
      $this->field->addRules(
         new RequiredRule,
         new MinValueRule(1),
         new MaxValueRule(10)
      );

      # the string field starts with a NotBoolean rule
      $this->assertEquals(4, count($this->field->getRules()));
   }
}


