<?php

namespace sndsgd\field;

use \sndsgd\field\rule\Required as RequiredRule;
use \sndsgd\field\rule\MinValue as MinValueRule;
use \sndsgd\field\rule\MaxValue as MaxValueRule;


class FieldTest extends \PHPUnit_Framework_TestCase
{
   public function setUp()
   {
      $this->field = Field::str('test');
   }

   public function testCallStatic()
   {
      $field = Field::bool('name');
      $this->assertTrue($field instanceof BooleanField);
      $field = Field::boolean('name');
      $this->assertTrue($field instanceof BooleanField);

      $field = Field::int('name');
      $this->assertTrue($field instanceof IntegerField);
      $field = Field::integer('name');
      $this->assertTrue($field instanceof IntegerField);

      $field = Field::flt('name');
      $this->assertTrue($field instanceof FloatField);
      $field = Field::float('name');
      $this->assertTrue($field instanceof FloatField);

      $field = Field::str('name');
      $this->assertTrue($field instanceof StringField);
      $field = Field::string('name');
      $this->assertTrue($field instanceof StringField);
   }

   /**
    * @expectedException InvalidArgumentException
    */
   public function testCallStaticTypeException()
   {
      Field::blegh('jimbo');
   }

   /**
    * @expectedException InvalidArgumentException
    */
   public function testCallStaticNameException()
   {
      Field::int([]);
   }

   public function testSetGetDescription()
   {
      $desc = 'just a test description';
      $this->field->setDescription($desc);
      $this->assertEquals($desc, $this->field->getDescription());
   }

   /**
    * @expectedException InvalidArgumentException
    */
   public function testSetDescriptionException()
   {
      $this->field->setDescription(42);
   }

   public function testSetAndGetOption()
   {
      $data = ['one' => 1, 'two' => 2];
      $this->field->setOption($data);
      $this->assertEquals($data, $this->field->getOption());

      # remove the option 2 (value defaults to null)
      $this->field->setOption('two');
      $this->assertEquals(['one' => 1], $this->field->getOption());
      $this->assertEquals(1, $this->field->getOption('one'));


      $this->field->setOption('three', 3);
      $this->assertEquals(3, $this->field->getOption('three'));
      $this->assertEquals(1, $this->field->getOption('one'));
      $this->assertNull($this->field->getOption('two'));
   }

   /**
    * @expectedException InvalidArgumentException
    */
   public function testSetOptionException()
   {
      $this->field->setOption('doesnt-exist');
   }

   public function testSetGetExportName()
   {
      $this->field->setExportName('shrt');
      $this->assertEquals('shrt', $this->field->getExportName());
   }

   /**
    * @expectedException InvalidArgumentException
    */
   public function testSetExportNameException()
   {
      $this->field->setExportName(42);
   }


   public function testSetExportNormal()
   {
      $this->field->addValue(42);
      $this->field->setExportHandler(Field::EXPORT_NORMAL);
      $this->assertEquals(42, $this->field->exportValue());
      $this->field->addValue(84);
      $this->assertEquals([42, 84], $this->field->exportValue());
   }

   public function testSetExportArray()
   {
      $this->field->addValue(42);
      $this->field->setExportHandler(Field::EXPORT_ARRAY);
      $this->assertEquals([42], $this->field->exportValue());
      $this->field->addValue(84);
      $this->assertEquals([42, 84], $this->field->exportValue());
   }

   /**
    * @expectedException Exception
    */
   public function testSetExportSkipException()
   {
      $this->field->setExportHandler(Field::EXPORT_SKIP);
      $this->field->exportValue();
   }

   /**
    * @expectedException InvalidArgumentException
    */
   public function testSetExportHandlerException()
   {
      $this->field->setExportHandler([]);
   }

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

   public function testHasValue()
   {
      $this->assertFalse($this->field->hasValue());
      $this->field->addValue(10);
      $this->assertTrue($this->field->hasValue());
   }

   public function testGetValue()
   {
      $val = 'default-value';
      $this->field->setDefault($val);
      $this->assertEquals($val, $this->field->getValue());
   }

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
    * @expectedException InvalidArgumentException
    */
   public function testAddRulesNotRuleException()
   {
      $this->field->addRules(42);
   }

   /**
    * @expectedException Exception
    */
   public function testAddRulesDupeRuleException()
   {
      $this->field->addRules(
         new RequiredRule,
         new RequiredRule
      );
   }

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

   // public function testValidateUpdateValue()
   // {
   //    $coll = new Collection();
   //    $coll->addFields($this->field);
   //    $this->field->addRules(
   //       Rule::addEmoticon(function($v, $d, $n, $i, $c) {
   //          return $v.' '.$d;
   //       }, ':)')
   //    );

   //    $this->field->addValue('hello');
   //    $this->field->validate();
   //    $this->assertEquals('hello :)', $this->field->exportValue());
   // }
}


