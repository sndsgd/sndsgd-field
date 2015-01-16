<?php

namespace sndsgd\field;

use \ReflectionClass;
use \sndsgd\field\Error;
use \sndsgd\field\rule\RequiredRule;


class RuleTest extends \PHPUnit_Framework_TestCase
{
   public function setUp()
   {
      $this->rule = new RequiredRule;
   }

   public function testValidate()
   {
      $this->assertFalse($this->rule->validate());

      $this->rule->setValue('');
      $this->assertFalse($this->rule->validate());

      $this->rule->setValue(1);
      $this->assertTrue($this->rule->validate());
   }

   public function testSetMessage()
   {
      $message = 'required';
      $this->rule->setMessage($message);
      $ref = new ReflectionClass('sndsgd\\field\\rule\\RequiredRule');
      $property = $ref->getProperty('message');
      $property->setAccessible(true);
      $this->assertEquals($message, $property->getValue($this->rule));
   }

   /**
    * @expectedException InvalidArgumentException
    */
   public function testConstructorException()
   {
      new RequiredRule(42);
   }

   /**
    * @expectedException InvalidArgumentException
    */
   public function testSetMessageException()
   {
      $this->rule->setMessage(42);
   }
}

