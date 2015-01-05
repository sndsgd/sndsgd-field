<?php

namespace sndsgd\field\rule;

use \sndsgd\Field;
use \sndsgd\field\ValidationError;


class ClosureTest extends RuleTestCase
{
   public static function stringValidatorTest($v, $d, $n, $i, $c)
   {
      return $v;
   }

   public function setUp()
   {
      $this->rule = new Closure(function($v, $d=null, $n=null, $i=null, $c=null) {
         if (!is_int($v)) {
            return new ValidationError('expecting an integer', $v, $n, $i);
         }
         return ($v % 2 === 0) ? 'even' : 'odd';
      });
   }

   public function test()
   {
      $rule = $this->rule;

      # success
      $this->assertEquals('even', $rule->validate(10));
      $this->assertEquals('even', $rule->validate(2));
      $this->assertEquals('odd', $rule->validate(3));
      $this->assertEquals('odd', $rule->validate(7));
   
      # failure
      $this->assertValidationError($rule->validate('abc'));
      $this->assertValidationError($rule->validate([]));
      $this->assertValidationError($rule->validate('10'));

      $msg = 'provided value is not an integer';
      $rule->setMessage($msg);
      $result = $rule->validate('some string');
      $this->assertValidationError($result);
      $this->assertEquals($msg, $result->getMessage());
   }

   public function testGetClass()
   {
      $res = $this->rule->getClass();
      $this->assertTrue(is_string($res));

      $r = new Closure(__CLASS__.'::stringValidatorTest');
      $this->assertTrue(is_string($r->getClass()));
   }

   /**
    * @expectedException InvalidArgumentException
    */
   public function testConstructorNotCallableException()
   {
      new Closure([]);
   }

   public function testAddMultipleClosures()
   {
      $field = Field::string('test')
         ->addRules(
            $this->rule,
            new Closure(function($v, $d, $n, $i, $c) {
               return $v;
            }),
            new Closure(function($v, $d, $n, $i, $c) {
               return $v;
            }),
            new Closure(function($v, $d, $n, $i, $c) {
               return $v;
            }),
            new Closure(function($v, $d, $n, $i, $c) {
               return $v;
            })
         );
   }
}

