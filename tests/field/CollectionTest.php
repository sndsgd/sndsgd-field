<?php

namespace sndsgd\field;

use \sndsgd\Event;
use \sndsgd\Field;
use \sndsgd\field\rule\Required as RequiredRule;


class CollectionTest extends \PHPUnit_Framework_TestCase
{
   protected function setUp()
   {
      $this->coll = new Collection();
      $this->coll->addFields(
         Field::str('title')
            ->addAliases('t')
            ->addRules(new RequiredRule),
         Field::str('camera')
            ->addAliases('c')
            ->addRules(new RequiredRule),
         [
            Field::int('image-width')
               ->addRules(new RequiredRule),
            Field::int('image-height')
               ->addRules(new RequiredRule),
            Field::str('image-format')
               ->addRules(new RequiredRule)
         ]
      );

      $this->values = [
         'title' => ['my title', 'another title'],
         'camera' => 'a fancy one',
         'image-width' => 1024,
         'image-height' => 768,
         'image-format' => 'jpeg'
      ];
   }

   /**
    * @expectedException Exception
    */
   public function testaddFieldsNameException()
   {
      $this->coll->addFields(Field::int('title'));
   }

   /**
    * @expectedException Exception
    */
   public function testaddFieldsAliasException()
   {
      $this->coll->addFields(Field::int('toobie')->addAliases('t'));
   }

   public function testGetField()
   {
      $this->assertTrue($this->coll->getField('title') !== null);
      $this->assertTrue($this->coll->getField('camera') !== null);
      $this->assertTrue($this->coll->getField('image-width') !== null);
      $this->assertTrue($this->coll->getField('image-height') !== null);
      $this->assertTrue($this->coll->getField('image-format') !== null);
      $this->assertTrue($this->coll->getField('doesnt-exist') === null);
   }

   public function testGetFields()
   {
      $fields = $this->coll->getFields();
      foreach ($fields as $name => $field) {
         $this->assertEquals($field, $this->coll->getField($name));
      }
   }

   public function testSetGetData()
   {
      $this->coll->addData('one', 1);
      $this->coll->addData('two', '2');
      $obj = new \StdClass;
      $this->coll->addData('obj', $obj);

      $this->assertEquals(1, $this->coll->getData('one'));
      $this->assertEquals('2', $this->coll->getData('two'));
      $this->assertEquals($obj, $this->coll->getData('obj'));

      # get non existing value
      $this->assertNull($this->coll->getData('doesnt-exist'));

      # get all data
      $this->assertEquals([
         'one' => 1,
         'two' => 2,
         'obj' => $obj
      ], $this->coll->getData());
   }


   public function testValidationEvents()
   {
      $beforeValidate = \Closure::bind(function(Event $ev) {
         $collection = $ev->getData('collection');
         $errors = $collection->getValidationErrors();
         $this->assertEquals(0, count($errors));
      }, $this);

      $afterValidate = \Closure::bind(function(Event $ev) {
         $collection = $ev->getData('collection');
         $errors = $collection->getValidationErrors();
         $this->assertEquals(5, count($errors));
      }, $this);

      $this->coll->on('beforeValidate', $beforeValidate);
      $this->coll->on('afterValidate', $afterValidate);

      $this->assertFalse($this->coll->validate());
   }

   public function testBeforeValidateFail()
   {
      $coll = new Collection;
      $coll->on('beforeValidate', function(Event $ev) {
         return false;
      });
      $this->assertFalse($coll->validate());
   }

   public function testAfterValidateFail()
   {
      $coll = new Collection;
      $coll->on('afterValidate', function(Event $ev) {
         return false;
      });
      $this->assertFalse($coll->validate());

      $coll = new Collection;
      $coll->on('afterValidate', function(Event $ev) {
         $coll = $ev->getData('collection');
         $err = new ValidationError('something went wrong', '', 'name', 0);
         $coll->addValidationError($err);
         return true;
      });
      $this->assertFalse($coll->validate());
   }

   public function testValidationErrors()
   {
      $this->assertFalse($this->coll->hasValidationErrors());

      $ve1 = new ValidationError('enter a title', '', 'title', 0);
      $count = $this->coll->addValidationError($ve1);
      $this->assertEquals(1, $count);
      $this->assertTrue($this->coll->hasValidationErrors());
      $this->assertEquals([$ve1], $this->coll->getValidationErrors('title'));

      $ve2 = new ValidationError('enter a camera', '', 'camera', 0);
      $count = $this->coll->addValidationError($ve2, true);
      $this->assertEquals(2, $count);
      $this->assertTrue($this->coll->hasValidationErrors());
      
      $errors = $this->coll->getValidationErrors();
      $this->assertEquals($ve1, $errors[1]);
      $this->assertEquals($ve2, $errors[0]);
   }

   public function testAddValues()
   {
      $this->coll->addValues($this->values);
      $this->assertEquals($this->values, $this->coll->exportValues());
   }

   public function testAddValuesWithNonExistingField()
   {
      $this->coll->addValues(array_merge($this->values, [
         'does-not-exist' => 1
      ]));
      $this->assertEquals($this->values, $this->coll->exportValues());
      $this->assertTrue($this->coll->hasValidationErrors());
      $errors = $this->coll->getValidationErrors();
      $this->assertEquals(1, count($errors));
   }

   public function testValidate()
   {
      $result = $this->coll->validate();
      $this->assertFalse($result);
      $errors = $this->coll->getValidationErrors();
      $this->assertEquals(5, count($errors));
   }

   public function testValidate2()
   {
      $this->coll->addValues([
         'image-width' => '1024',
         'image-height' => 768,
         'image-format' => 'jpeg'
      ]);
      $result = $this->coll->validate();
      $this->assertFalse($result);
      $errors = $this->coll->getValidationErrors();
      $this->assertEquals(2, count($errors));
   }

   public function testExportFieldValue()
   {
      $this->coll->addValues($this->values);
      $this->assertEquals(1024, $this->coll->exportFieldValue('image-width'));
      $this->assertEquals(
         $this->values['title'], 
         $this->coll->exportFieldValue('title')
      );
   }

   /**
    * @expectedException InvalidArgumentException
    */
   public function testExportFieldValueInvalidArgException()
   {
      $this->coll->addValues($this->values);
      $this->coll->exportFieldValue(42);
   }

   /**
    * @expectedException InvalidArgumentException
    */
   public function testExportFieldValueDoesntExistException()
   {
      $this->coll->addValues($this->values);
      $this->coll->exportFieldValue('nope');
   }
}

