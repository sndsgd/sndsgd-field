<?php

namespace sndsgd;

use \sndsgd\field\BooleanField;
use \sndsgd\field\DateField;
use \sndsgd\field\FloatField;
use \sndsgd\field\IntegerField;
use \sndsgd\field\StringField;
use \sndsgd\field\Collection;
use \sndsgd\field\rule\RequiredRule;
use \sndsgd\field\rule\MaxValueCountRule;


class KitchenSinkTest extends \PHPUnit_Framework_TestCase
{
   public function setUp()
   {
      $this->form = new Collection([
         new BooleanField('boolean'),
         new DateField('date'),
         new FloatField('float'),
         new IntegerField('integer'),
         new StringField('string'),
      ]);
   }

   public function testSuccess()
   {
      $date = '2015-01-01 01:02:03';
      $this->form->addValues([
         'boolean' => 'on',
         'date' => $date,
         'float' => '4.2',
         'integer' => '42',
         'string' => 'test'
      ]);

      $this->assertTrue($this->form->validate());
      $values = $this->form->exportValues();
      $this->assertTrue($values['boolean']);
      $this->assertInstanceOf('DateTime', $values['date']);
      $this->assertEquals($date, $values['date']->format('Y-m-d H:i:s'));
      $this->assertSame(42, $values['integer']);
      $this->assertSame(4.2, $values['float']);
      $this->assertEquals('test', $values['string']);
   }

   public function testFailureUnknownField()
   {
      $this->form->addValues([ 'not-defined' => '42' ]);
      $this->assertFalse($this->form->validate());
   }
}


