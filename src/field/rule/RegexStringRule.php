<?php

namespace sndsgd\field\rule;


/**
 * Ensure a value is a valid regex
 */
class RegexStringRule extends \sndsgd\field\Rule
{
   /**
    * {@inheritdoc}
    */
   public function getMessage()
   {
      $len = strlen('preg_match(): ');
      $message = lcfirst(substr($this->message, $len));
      return "invalid regex: {$message}";
   }

   /**
    * {@inheritdoc}
    */
   public function validate()
   {
      if (@preg_match($this->value, null) === false) {
         $error = error_get_last();
         $this->message = $error['message'];
         return false;
      }
      return true;
   }
}

