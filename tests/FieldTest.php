<?php

namespace sndsgd;

use \sndsgd\Event;
use \sndsgd\field\BooleanField;
use \sndsgd\field\IntegerField;
use \sndsgd\field\FloatField;
use \sndsgd\field\StringField;
use \sndsgd\field\Collection;
use \sndsgd\field\rule\RequiredRule;
use \sndsgd\field\rule\MinValueRule;
use \sndsgd\field\rule\MaxValueRule;


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
      $this->field = new StringField('test');
   }

   /**
    * @covers ::__construct
    * @expectedException InvalidArgumentException
    */
   public function testSetNameException()
   {
      new StringField(42);
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
    * @covers ::getName
    */
   public function testGetName()
   {
      $name = 'test';
      $field = new StringField($name);
      $this->assertEquals($name, $field->getName());
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
      $this->field->addValue(1);
      $this->field->addValue(2);
      $this->assertEquals(1, $this->field->getValue(0));
      $this->assertEquals(2, $this->field->getValue(1));
      $this->assertEquals(null, $this->field->getValue(2));
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
    * @covers ::addRule
    * @expectedException Exception
    */
   public function testAddRulesDupeRuleException()
   {
      $this->field->addRule(new RequiredRule);
      $this->field->addRule(new RequiredRule);
   }

   /**
    * @covers ::addRules
    * @covers ::validate
    */
   public function testValidateNotRequired()
   {
      $this->field->addRules([
         new MinValueRule(1),
         new MaxValueRule(10)
      ]);

      // no value, but not required (valid)
      $this->assertTrue($this->field->validate());
   }

   /**
    * Whenever a required rule is added to a field, it becomes the first rule
    * 
    * @covers ::addRules
    * @covers ::getRules
    */
   public function testAddRequiredRuleOrder()
   {
      $field = new StringField('test');
      $rules = $field->getRules();
      $this->assertCount(0, $rules);

      $field->addRule(new MinValueRule(1));
      $rules = $field->getRules();
      $this->assertCount(1, $rules);

      $field->addRule(new RequiredRule);
      $rules = $field->getRules();
      $this->assertCount(2, $rules);

      $this->assertInstanceOf('sndsgd\\field\\rule\\RequiredRule', array_shift($rules));
      $this->assertInstanceOf('sndsgd\\field\\rule\\MinValueRule', array_shift($rules));
   }
}


