<?php

namespace sndsgd\field;

use \sndsgd\Event;
use \sndsgd\Field;
use \sndsgd\field\BooleanField;
use \sndsgd\field\IntegerField;
use \sndsgd\field\StringField;
use \sndsgd\field\rule\RequiredRule;


class BeforeValidateFailCollection extends Collection
{
   protected function beforeValidate()
   {
      return false;
   }
}

class AfterValidateFailCollection extends Collection
{
   protected function AfterValidate()
   {
      return false;
   }
}


/**
 * @coversDefaultClass \sndsgd\field\Collection
 */
class CollectionTest extends \PHPUnit_Framework_TestCase
{
   protected function setUp()
   {
      $this->coll = new Collection();
      $this->coll->addFields([
         (new StringField('title'))
            ->addAliases('t')
            ->addRule(new RequiredRule),
         (new StringField('camera'))
            ->addAliases('c')
            ->addRule(new RequiredRule),
         (new IntegerField('image-width'))
            ->addRule(new RequiredRule),
         (new IntegerField('image-height'))
            ->addRule(new RequiredRule),
         (new StringField('image-format'))
            ->addRule(new RequiredRule)
      ]);

      $this->values = [
         'title' => ['my title', 'another title'],
         'camera' => 'a fancy one',
         'image-width' => 1024,
         'image-height' => 768,
         'image-format' => 'jpeg'
      ];
   }

   /**
    * @covers ::__construct
    */
   public function testAddFieldsConstructor()
   {
      $coll = new Collection([ new BooleanField('test') ]);
      $field = $coll->getField('test');
      $this->assertInstanceOf('sndsgd\\field\\BooleanField', $field);
   }

   /**
    * @expectedException \sndsgd\field\collection\DuplicateFieldNameException
    */
   public function testAddFieldNameException()
   {
      $this->coll->addField(new IntegerField('title'));
   }

   /**
    * @expectedException \sndsgd\field\collection\DuplicateFieldAliasException
    */
   public function testAddFieldAliasException()
   {
      $this->coll->addField((new IntegerField('toobie'))->addAliases('t'));
   }

   public function testCount()
   {
      $this->assertCount(5, $this->coll);
   }

   public function testRemoveField()
   {
      $field = (new StringField('remove-me'))->addAliases('rm');
      $this->coll->addField($field);
      $this->assertTrue($this->coll->removeField('remove-me'));
      $this->coll->addField($field);
      $this->assertTrue($this->coll->removeField('rm'));
      $this->assertFalse($this->coll->removeField('rm'));
   }

   public function testGetField()
   {
      # get by name
      $field = $this->coll->getField('title');
      $this->assertEquals('title', $field->getName());

      # get by alias
      $field = $this->coll->getField('t');
      $this->assertEquals('title', $field->getName());
   }

   public function testGetFields()
   {
      $this->assertCount(5, $this->coll->getFields());
   }

   public function testGetFieldsVisibility()
   {
      $title = $this->coll->getField("title");
      $title->setVisibility(Field::VISIBILITY_PRIVATE);

      $this->assertCount(4, $this->coll->getFields());
      $this->assertCount(1, $this->coll->getFields(Field::VISIBILITY_PRIVATE));

      $both = Field::VISIBILITY_PRIVATE | Field::VISIBILITY_PUBLIC;
      $this->assertCount(5, $this->coll->getFields($both));
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

   public function testBeforeValidateFail()
   {
      $coll = new BeforeValidateFailCollection;
      $this->assertFalse($coll->validate());
   }

   public function testAfterValidateFail()
   {
      $coll = new AfterValidateFailCollection;
      $this->assertFalse($coll->validate());
   }

   private function createTestErrors()
   {
      $titleField = new StringField('title');
      $cameraField = new StringField('camera');

      $e1 = new Error('enter a title');
      $e1->setName($titleField->getName());
      $this->assertEquals(1, $this->coll->addError($e1));
      $this->assertTrue($this->coll->hasErrors());
      $this->assertEquals([$e1], $this->coll->getErrors('title'));

      # create another error, and prepend it to the array of errors
      $e2 = new Error('enter a camera');
      $e2->setName($cameraField->getName());
      $this->assertEquals(2, $this->coll->addError($e2, true));
      $this->assertTrue($this->coll->hasErrors());

      return [$e2, $e1];
   }

   public function testErrors()
   {
      $this->assertFalse($this->coll->hasErrors());
      $testErrors = $this->createTestErrors();
      $errors = $this->coll->getErrors();
      $this->assertEquals($testErrors[0], $errors[0]);
      $this->assertEquals($testErrors[1], $errors[1]);
   }

   public function testExportErrors()
   {
      $this->assertFalse($this->coll->hasErrors());
      $testErrors = $this->createTestErrors();
      $errors = $this->coll->exportErrors();
      $this->assertEquals($testErrors[0]->export(), $errors[0]);
      $this->assertEquals($testErrors[1]->export(), $errors[1]);
   }

   public function testAddValues()
   {
      $this->coll->addValues($this->values);
      $this->assertEquals($this->values, $this->coll->exportValues());
   }

   public function testAddValuesWithNonExistingField()
   {
      # use 2 values to ensure the
      $this->coll->addValues(array_merge($this->values, [
         'does-not-exist' => [1,2]
      ]));
      $this->assertEquals($this->values, $this->coll->exportValues());
      $this->assertTrue($this->coll->hasErrors());
      $errors = $this->coll->getErrors();
      $this->assertEquals(1, count($errors));
   }

   public function testValidate()
   {
      $this->assertFalse($this->coll->validate());
      $this->assertCount(5, $this->coll->getErrors());
   }

   public function testValidate2()
   {
      $this->coll->addValues([
         'image-width' => '1024',
         'image-height' => 768,
         'image-format' => 'jpeg'
      ]);
      $this->assertFalse($this->coll->validate());
      $this->assertCount(2, $this->coll->getErrors());
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

   public function testExportFieldValueOverrideHandler()
   {
      $this->coll->addValues(["title" => "test 42"]);
      $result = $this->coll->exportFieldValue('title');
      $this->assertEquals("test 42", $result);

      $result = $this->coll->exportFieldValue('title', Field::EXPORT_ARRAY);
      $this->assertEquals(["test 42"], $result);
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
    * @expectedException \sndsgd\field\UnknownFieldException
    */
   public function testExportFieldValueDoesntExistException()
   {
      $this->coll->addValues($this->values);
      $this->coll->exportFieldValue('nope');
   }
}

