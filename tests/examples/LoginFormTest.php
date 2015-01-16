<?php

namespace sndsgd;

use \sndsgd\Event;
use \sndsgd\field\BooleanField;
use \sndsgd\field\StringField;
use \sndsgd\field\Collection;
use \sndsgd\field\rule\RequiredRule;
use \sndsgd\field\rule\MaxValueCountRule;



/**
 * @coversDefaultClass \sndsgd\Field
 */
class LoginFormTest extends \PHPUnit_Framework_TestCase
{
   public function setUp()
   {
      $this->form = new Collection([
         (new StringField('username'))
            ->addData('hint', 'enter your password')
            ->addRules([
               new RequiredRule,
               new MaxValueCountRule(1)
            ]),
         (new StringField('password'))
            ->addRules([
               new RequiredRule,
               new MaxValueCountRule(1)
            ]),
         new BooleanField('remember')
      ]);
   }

   public function testSuccess()
   {
      $this->form->addValues([
         'username' => 'superuser',
         'password' => 'letmein123',
         'remember' => 'on'
      ]);

      $this->assertTrue($this->form->validate());
      $expect = [
         'username' => 'superuser',
         'password' => 'letmein123',
         'remember' => true
      ];
      $this->assertEquals($expect, $this->form->exportValues());
   }

   public function testFailure()
   {
      $this->form->addValues([
         'username' => 'superuser',
         'password' => '',
      ]);

      $this->assertFalse($this->form->validate());

      $expect = [
         'username' => 'superuser',
         'password' => '',
         'remember' => false
      ];
      $this->assertEquals($expect, $this->form->exportValues());
   }
}


