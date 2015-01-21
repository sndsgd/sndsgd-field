<?php

namespace sndsgd\field;

use \Exception;
use \InvalidArgumentException;
use \sndsgd\Arr;
use \sndsgd\Field;
use \sndsgd\field\collection\DuplicateFieldNameException;
use \sndsgd\field\collection\DuplicateFieldAliasException;


/**
 * A base class for a collection of fields
 */
class Collection implements \Countable
{
   use \sndsgd\data\Manager;

   /**
    * The data event key for the collection object whenever an event is
    * fired at a field
    * 
    * @var string
    */
   const EVENT_DATA_KEY = 'collection';

   /**
    * All fields currently in the collection
    * 
    * @var array.<string,sndsgd\Field>
    */
   protected $fields = [];

   /**
    * A map of field aliases pointing to field names
    *
    * @var array.<string,string>
    */
   protected $fieldAliases = [];

   /**
    * Any errors encountered during validation
    * 
    * @var array.<sndsgd\field\Error>|null
    */
   protected $errors = [];

   /**
    * Create a new field collection
    * 
    * @param array.<sndsgd\Field>|null $fields Fields to add to the collection
    */
   public function __construct(array $fields = [])
   {
      if ($fields) {
         $this->addFields($fields);
      }
   }

   /**
    * @see http://php.net/manual/en/countable.count.php
    * @return int
    */
   public function count()
   {
      return count($this->fields);
   }

   /**
    * Perform actions immediately before validation
    *
    * If this method returns anything other than boolean true, field 
    * validation will be skipped, and validation will fail
    * Override this method in a subclass if you need to pre-validate state 
    * @return boolean
    */
   protected function beforeValidate()
   {
      return true;
   }

   /**
    * Perform actions immediately after validation
    *
    * If this method returns anything other than boolean true validation 
    * will fail
    * Override this method in a subclass if you need to post-validate state 
    * @return boolean
    */
   protected function afterValidate()
   {
      return true;
   }

   /**
    * Define a field
    * 
    * @param sndsgd\Field $field The field to define
    * @return sndsgd\field\Collection
    */
   public function addField(Field $field)
   {
      $name = $field->getName();
      if (array_key_exists($name, $this->fields)) {
         throw new DuplicateFieldNameException($name);
      }
      $this->fields[$name] = $field;
      foreach ($field->getAliases() as $alias) {
         if (array_key_exists($alias, $this->fieldAliases)) {
            throw new DuplicateFieldAliasException($alias);
         }
         $this->fieldAliases[$alias] = $name;
      }

      return $this;
   }

   /**
    * Define multiple fields
    *
    * @param array.<sndsgd\field\Field> $fields
    * @return sndsgd\field\Collection This instance
    */
   public function addFields(array $fields)
   {
      foreach ($fields as $field) {
         $this->addField($field);
      }
      return $this;
   }

   /**
    * Remove a field from the collection
    * 
    * @param string $name A field name or alias
    * @return boolean Whether or not a field was removed
    */
   public function removeField($name)
   {
      if (($field = $this->getField($name)) === null) {
         return false;
      }
   
      foreach ($field->getAliases() as $alias) {
         unset($this->fieldAliases[$alias]);
      }
      unset($this->fields[$name]);
      return true;
   }

   /**
    * Get a field instance
    * 
    * @param string $name An alias or name of the field to get
    * @return sndsgd\Field|null
    * @return sndsgd\Field The field was found
    * @return null The field was NOT found
    */
   public function getField($name)
   {
      if (array_key_exists($name, $this->fields)) {
         return $this->fields[$name];
      }
      else if (array_key_exists($name, $this->fieldAliases)) {
         $name = $this->fieldAliases[$name];
         return $this->fields[$name];
      }
      return null;
   }

   /**
    * Get an associative array of the fields in this collection
    * 
    * @return array.<string,sndsgd\Field>
    */
   public function getFields()
   {
      return $this->fields;
   }

   /**
    * Add values to the fields in the collection
    *
    * @param array.<string,mixed> $fieldValues
    */
   public function addValues(array $fieldValues)
   {
      foreach ($fieldValues as $fieldName => $values) {
         $values = Arr::cast($values);
         if (null === ($field = $this->getField($fieldName))) {
            $message = "unknown field";
            $len = count($values);
            if ($len !== 1) {
               $message .= " (encountered $len values)";
            }
            $error = new Error($message);
            $error->setName($fieldName);
            $this->addError($error);
         }
         else {
            foreach ($values as $index => $value) {
               $field->addValue($value);
            }   
         }
      }
   }

   /**
    * Validate the all the fields
    * 
    * @return boolean
    * @return boolean:true All fields validated successfully
    * @return boolean:false One or more fields failed to validate
    */
   public function validate()
   {
      # addValues adds validation errors for unknown values
      $errs = count($this->errors);

      $dataKey = constant(get_called_class().'::EVENT_DATA_KEY');

      if ($this->beforeValidate() !== true) {
         return false;
      }
      foreach ($this->fields as $field) {
         if ($field->validate($this) === false) {
            foreach ($field->getErrors() as $error) {
               $this->addError($error);
               $errs++;
            }
         }
      }
      return (
         $errs > 0 ||
         $this->afterValidate() !== true ||
         count($this->errors) > 0
      ) ? false : true;
   }

   /**
    * Add a validation error
    * 
    * @param sndsgd\field\Error $error
    * @param boolean $prepend Add the error to the beginning
    * @return integer The total number of validation errors
    */
   public function addError(Error $error, $prepend = false)
   {
      if ($prepend === true) {
         array_unshift($this->errors, $error);
      }
      else {
         $this->errors[] = $error;
      }
      return count($this->errors);
   }

   /**
    * Determine if any validation errors exist
    * 
    * @return boolean
    */
   public function hasErrors()
   {
      return (count($this->errors) !== 0);
   }

   /**
    * Get validation errors for one or all fields
    *
    * @param string|null $name The name of a field to get validation errors for
    * @return array.<sndsgd\field\Error>
    */
   public function getErrors($name = null)
   {
      if ($name === null) {
         return $this->errors;
      }

      $ret = [];
      foreach ($this->errors as $error) {
         if ($error->getName() === $name) {
            $ret[] = $error;
         }
      }
      return $ret;
   }

   /**
    * Convenience method to get a particular field value
    * 
    * @return mixed
    * @throws InvalidArgumentException If provided name does not exist
    */
   public function exportFieldValue($name)
   {
      if (!is_string($name)) {
         throw new InvalidArgumentException(
            "invalid value provided for 'name'; ".
            "expecting a field name as string"
         );
      }
      else if (($field = $this->getField($name)) == null) {
         throw new UnknownFieldException($name);
      }
      return $field->exportValue();
   }

   /**
    * Get all field values using their respective export handlers
    *
    * @return array.<string,mixed>
    */
   public function exportValues()
   {
      $ret = [];
      foreach ($this->fields as $field) {
         if ($field->getExportHandler() !== Field::EXPORT_SKIP) {
            $ret[$field->getName()] = $field->exportValue();
         }
      }
      return $ret;
   }
}

