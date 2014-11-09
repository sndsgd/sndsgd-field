<?php

use \sndsgd\field\rule\Closure as ClosureRule;
use \sndsgd\field\ValidationError;


class ClosureTest extends \PHPUnit_Framework_TestCase
{
   public function test()
   {
      $fn = function($v, $d=null, $n=null, $i=null, $c=null) {
         if (!is_int($v)) {
            return new ValidationError('expecting an integer', $v, $n, $i);
         }
         return ($v % 2 === 0) ? 'even' : 'odd';
      };

      $rule = new ClosureRule($fn);

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

   /**
    * @expectedException InvalidArgumentException
    */
   public function testConstructorNotCallableException()
   {
      new ClosureRule([]);
   }
}

