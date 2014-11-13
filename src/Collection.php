<?php

namespace sndsgd\field;

use \Exception;
use \sndsgd\util\Arr;


/**
 * A base class for a collection of fields
 */
class Collection
{
   use \sndsgd\event\Target;

   /**
    * All fields currently in the collection
    * 
    * @var array.<string,sndsgd\field\Field>
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
    * @var array.<sndsgd\field\ValidationError>|null
    */
   protected $validationErrors = null;

   /**
    * Store data that required expensive operations to get
    *
    * @var array.<string,mixed>
    */
   protected $data = [];

   /**
    * Define a field
    * 
    * @param sndsgd\field\Field $field The field to define
    * @return sndsgd\field\Collection
    */
   protected function addField(Field $field)
   {
      $name = $field->getName();
      if (array_key_exists($name, $this->fieldAliases)) {
         throw new Exception(
            "failed to define field; the name '$name' is already defined"
         );
      }
      $this->fields[$name] = $field;
      $this->fieldAliases[$name] = $name;

      foreach ($field->getAliases() as $alias) {
         if (array_key_exists($alias, $this->fieldAliases)) {
            throw new Exception(
               "failed to define field; the alias '$alias' is already in use"
            );
         }
         $this->fieldAliases[$alias] = $name;
      }

      return $this;
   }

   /**
    * Define fields
    *
    * @param sndsgd\field\Field $field,... 
    * @return sndsgd\field\Collection This instance
    */
   public function addFields()
   {
      foreach (func_get_args() as $field) {
         # allow an array of fields to be passed
         # its convenient to pass the result of function call this way
         if (is_array($field)) {
            call_user_func_array([$this, 'addFields'], $field);
         }
         else {
            $this->addField($field);
         }
      }
      return $this;
   }

   /**
    * Get a field instance
    * 
    * @param string $name An alias or name of the field to get
    * @return sndsgd\field\Field|null
    * @return sndsgd\field\Field The field was found
    * @return null The field was NOT found
    */
   public function getField($name)
   {
      if (!array_key_exists($name, $this->fieldAliases)) {
         return null;
      }
      $index = $this->fieldAliases[$name];
      return $this->fields[$index];
   }

   /**
    * Get an associative array of the fields in this collection
    * 
    * @return array.<string,sndsgd\field\Field>
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
         $field = $this->getField($fieldName);
         foreach (Arr::cast($values) as $index => $value) {
            if ($field === null) {
               $this->addValidationError(
                  new ValidationError(
                     'unknown parameter',
                     $value,
                     $fieldName,
                     $index
                  )
               );
            }
            else {
               $field->addValue($value);
            }
         }
      }
   }


   /**
    * Stash data in this object
    *
    * Helpful when performing expensive operations during validation on data
    * that will be required later in the script
    * @param string $key The name to stash data under
    * @param mixed $value Whatever needs to be stashed
    * @return void
    */
   public function addData($key, $value)
   {
      $this->data[$key] = $value;
   }

   /**
    * Retrieve data from this object
    *
    * @param string $key The name to stash data under
    * @param mixed $value Whatever needs to be stashed
    * @return void
    */
   public function getData($key = null)
   {
      if ($key === null) {
         return $this->data;
      }
      else if (!array_key_exists($key, $this->data)) {
         return null;
      }
      return $this->data[$key];
   }

   /**
    * Validate the all the fields
    * 
    * @return boolean True if no errors were encountered, otherwise false
    */
   public function validate()
   {
      $this->validationErrors = [];

      if ($this->fire('beforeValidate', ['collection' => $this]) === false) {
         return false;
      }

      foreach ($this->fields as $field) {
         $field->validate($this);
      }

      if ($this->fire('afterValidate', ['collection' => $this]) === false) {
         return false;
      } 
      
      return (count($this->validationErrors) === 0);
   }

   /**
    * Add a validation error
    * 
    * @param sndsgd\field\ValidationError $error
    * @param boolean $unshift Add the error to the beginning
    * @return integer The total number of validation errors
    */
   public function addValidationError(ValidationError $error, $unshift = false)
   {
      if ($unshift === true) {
         array_unshift($this->validationErrors, $error);
      }
      else {
         $this->validationErrors[] = $error;
      }
      return count($this->validationErrors);
   }

   /**
    * Get validation errors for one or all fields
    *
    * @param string|null $name The name of a field to get validation errors for
    * @return array.<sndsgd\field\ValidationError>
    */
   public function getValidationErrors($name = null)
   {
      if ($name === null) {
         return $this->validationErrors;
      }

      $ret = [];
      foreach ($this->validationErrors as $validationError) {
         if ($validationError->getName() === $name) {
            $ret[] = $validationError;
         }
      }
      return $ret;
   }

   /**
    * Determine if any validation errors exist
    * 
    * @return boolean
    */
   public function hasValidationErrors()
   {
      return (count($this->validationErrors) !== 0);
   }

   /**
    * 
    */
   public function exportValues()
   {
      $ret = [];
      foreach ($this->fields as $field) {
         if ($field->getExportHandler() !== Field::EXPORT_SKIP) {
            $name = $field->getExportName();
            $ret[$field->getExportName()] = $field->exportValue();
         }
      }
      return $ret;
   }
}

