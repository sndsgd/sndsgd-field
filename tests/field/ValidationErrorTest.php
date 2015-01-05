<?php

namespace sndsgd\field;


class ValidationErrorTest extends \PHPUnit_Framework_TestCase
{
   public function testSimple()
   {
      $msg = 'bad value';
      $value = 42;
      $fieldName = 'name';
      $fieldIndex = 0;
      $ve = new ValidationError($msg, $value, $fieldName, $fieldIndex);
      $this->assertEquals($value, $ve->getValue());
      $this->assertEquals($fieldName, $ve->getName());
      $this->assertEquals($fieldIndex, $ve->getIndex());
      $this->assertEquals($msg, $ve->getMessage());

      $ve->addReference('other-field');
      $ve->addReference('other-field-2', 3);
      $expect = [['other-field', 0], ['other-field-2', 3]];
      $this->assertEquals($expect, $ve->getReferences());

      # 150 chars
      $value = "lqAiYeG6svTDReOhIwI6O0DVPqvlTpWlR40eKfl5NDF72Bo63nhdKDPtDq1RfaelhTxF8tRzthdiOuSl9QSDpABNjy6G3gS18pBSsfBkWUduYh0PnmidgqNLNfjM3itEXDf3QiL7AAjmQyJr1SUsrd";
      # 96 chars + ...
      $expect = "lqAiYeG6svTDReOhIwI6O0DVPqvlTpWlR40eKfl5NDF72Bo63nhdKDPtDq1RfaelhTxF8tRzthdiOuSl9QSDpABNjy6G3gS1...";
      $ve = new ValidationError($msg, $value, $fieldName, $fieldIndex);
      $this->assertEquals($expect, $ve->getValue());
   }
}

