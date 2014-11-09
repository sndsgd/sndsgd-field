<?php

use \sndsgd\field\rule\Closure as ClosureRule;
use \sndsgd\field\ValidationError;


class ClosureTest extends \PHPUnit_Framework_TestCase
{
   public function setUp()
   {
      $fn = function($v, $d=null, $n=null, $i=null, $c=null) {
         if (!is_int($v)) {
            return new ValidationError('expecting an integer', $v, $n, $i);
         }
         return ($v % 2 === 0) ? 'even' : 'odd';
      };
      $this->rule = new ClosureRule($fn);
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
      $this->assertTrue($rule->validate('abc') instanceof ValidationError);
      $this->assertTrue($rule->validate([]) instanceof ValidationError);
      $this->assertTrue($rule->validate('10') instanceof ValidationError);

      $msg = 'provided value is not an integer';
      $rule->setMessage($msg);
      $result = $rule->validate('some string');
      $this->assertTrue($result instanceof ValidationError);
      $this->assertEquals($msg, $result->getMessage());
   }

   public function testGetClass()
   {
      $res = $this->rule->getClass();
      $regex = '~[a-z][a-z0-9\\\\]+\\([0-9.]+\\)~i';
      $this->assertEquals(1,preg_match($regex, $res, $matches));
   }

   /**
    * @expectedException InvalidArgumentException
    */
   public function testConstructorNotCallableException()
   {
      new ClosureRule([]);
   }
}

