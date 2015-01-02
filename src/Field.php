<?php

namespace sndsgd;

use \Exception;
use \InvalidArgumentException;
use \sndsgd\field\Collection;
use \sndsgd\field\Rule;
use \sndsgd\field\exeception\DuplicateRuleException;
use \sndsgd\field\ValidationError;
use \sndsgd\util\Arr;


/**
 * A container for one or more values
 */
abstract class Field
{
   use \sndsgd\event\Target;

   // available export types
   const EXPORT_NORMAL = 0;
   const EXPORT_ARRAY = 1;
   const EXPORT_SKIP = 2;

   /**
    * Convenience method for creating fields by type
    * 
    * @param string $type The type of field to create 
    * @param array $args The arguments provided to the static method
    * @return sndsgd\field\Field 
    */
   public static function __callStatic($type, array $args)
   {
      $classes = [
         'bool' => 'sndsgd\\field\\BooleanField',
         'boolean' => 'sndsgd\\field\\BooleanField',
         'int' => 'sndsgd\\field\\IntegerField',
         'integer' => 'sndsgd\\field\\IntegerField',
         'flt' => 'sndsgd\\field\\FloatField',
         'float' => 'sndsgd\\field\\FloatField',
         'str' => 'sndsgd\\field\\StringField',
         'string' => 'sndsgd\\field\\StringField',
      ];

      if (!array_key_exists($type, $classes)) {
         throw new InvalidArgumentException(
            "unknown field type '$type'; "
         );
      }

      $class = $classes[$type];
      $fieldName = $args[0];
      return $class::create($fieldName);
   }

   /**
    * A human readable name for the field
    * 
    * @var string
    */
   protected $name;

   /**
    * One or more aliases for the field name
    *
    * Note: used for short names in cli scripts
    * @var array.<string,boolean>
    */
   protected $aliases = [];

   /**
    * A description of the field
    *
    * Note: use to auto-generate help
    * @var string
    */
   protected $description = 'no description provided';

   /**
    * The default value
    * 
    * @var string|integer|float|null
    */
   protected $defaultValue = null;

   /**
    * The value(s) are stored in an indexed array
    *
    * When the field has a value, this will always be an array of values
    * @var array.<string|integer|float|boolean>|null
    */
   protected $value = null;

   /**
    * Validation rules
    * 
    * @var array.<string,sndsgd\field\Rule>
    */
   protected $rules = [];

   /**
    * Optional data storage
    *
    * @var array.<
    */
   protected $options = [];

   /**
    * A custom name to export the field as
    * 
    * @var string|null
    */
   protected $exportName = null;

   /**
    * A custom handler for exporting the field
    * 
    * @var integer|callable
    */
   protected $exportHandler = self::EXPORT_NORMAL;


   /**
    * Create a new field instance
    * 
    * @param string $name The name of the field
    */
   public function __construct($name)
   {
      $this->setName($name);
   }

   /**
    * Set the name of the field
    * 
    * @param string $name
    * @return sndsgd\field\Field
    * @throws InvalidArgumentException
    */
   private function setName($name)
   {
      if (!is_string($name)) {
         throw new InvalidArgumentException(
            "invalid value provided for 'name'; expecting a string"
         );
      }
      $this->name = $name;
      return $this;
   }

   /**
    * Get the field name
    * 
    * @return string
    */
   public function getName()
   {
      return $this->name;
   }

   /**
    * Add one or more aliases to the field
    *
    * @param string $alias,... 
    * @return sndsgd\field\Field
    */
   public function addAliases($alias)
   {
      foreach (func_get_args() as $alias) {
         $this->aliases[$alias] = true;
      }
      return $this;
   }

   /**
    * Get all the aliases for the field
    * 
    * @return array.<string>
    */
   public function getAliases()
   {
      return array_keys($this->aliases);
   }

   /**
    * Set a human readable description describing the field
    * 
    * @param string $description
    * @return sndsgd\Field
    */
   public function setDescription($description)
   {
      if (!is_string($description)) {
         throw new InvalidArgumentException(
            "invalid value provided for 'description'; expecting a string"
         );
      }
      $this->description = $description;
      return $this;
   }

   /**
    * Get the human readable description
    * 
    * @return string
    */
   public function getDescription()
   {
      return $this->description;
   }

   /**
    * Set a default value
    * 
    * @param mixed $value
    * @return sndsgd\field\Field
    * @throws InvalidArgumentException If $value is not the appropriate type
    */
   abstract public function setDefault($value);

   /**
    * Get a default value
    * 
    * @return mixed
    */
   public function getDefault()
   {
      return $this->defaultValue;
   }

   /**
    * Set one or more option values
    * 
    * @return sndsgd\Field
    */
   public function setOption($key, $value = null)
   {
      if (is_array($key)) {
         foreach ($key as $k => $v) {
            $this->options[$k] = $v;
         }
      }
      else if ($value === null) {
         if (!array_key_exists($key, $this->options)) {
            throw new InvalidArgumentException(
               "invalid value provided for 'key'; ".
               "expecting the name of an option to remove as string"
            );
         }
         unset($this->options[$key]);
      }
      else {
         $this->options[$key] = $value;
      }
      return $this;
   }

   /**
    * Get one or all options
    *
    * @param string|null $key The value assoiciated with 
    * @return string|array|null
    */
   public function getOption($key = null)
   {
      if ($key === null) {
         return $this->options;
      }

      return (array_key_exists($key, $this->options))
         ? $this->options[$key]
         : null;
   }

   /**
    * Set a name to export the field as
    * 
    * @param string $name
    * @return sndsgd\Field
    * @throws InvalidArgumentException
    */
   public function setExportName($name)
   {
      if (!is_string($name)) {
         throw new InvalidArgumentException(
            "invalid value provided for 'name'; expecting a string"
         );
      }
      $this->exportName = $name;
      return $this;
   }

   /**
    * Get the name to export the field as
    * 
    * @return string
    */
   public function getExportName()
   {
      return ($this->exportName !== null)
         ? $this->exportName
         : $this->name;
   }

   /**
    * Set the method by which to export the value
    * 
    * @param integer|callable $type
    * @return sndsgd\Field
    * @throws InvalidArgumentException If $type is not valid
    */
   public function setExportHandler($type)
   {
      if (
         is_callable($type) || 
         $type === self::EXPORT_NORMAL ||
         $type === self::EXPORT_ARRAY ||
         $type === self::EXPORT_SKIP
      ) {
         $this->exportHandler = $type;
         return $this;   
      }

      throw new InvalidArgumentException(
         "invalid value provided for 'type'; ".
         "expecting a callable, or one of the following contstants: ".
         "sndsgd\Field::EXPORT_NORMAL, sndsgd\Field::EXPORT_ARRAY, or ".
         "sndsgd\Field::EXPORT_SKIP"
      );
   }

   /**
    * @return integer
    */
   public function getExportHandler()
   {
      return $this->exportHandler;
   }

   /**
    * Add a value
    * 
    * @param string|number $value The value to add
    * @return sndsgd\Field
    */
   public function addValue($value)
   {
      if ($this->value === null) {
         $this->value = [];
      }
      $this->value[] = $value;
      return $this;
   }

   /**
    * Set one or all values
    * 
    * @param string|integer|float|array.<string|integer|float> $value 
    * @param integer|null $index
    * @return sndsgd\Field
    */
   public function setValue($value, $index = null)
   {
      if ($index === null) {
         $this->value = Arr::cast($value);
      }
      else {
         $this->value[$index] = $value;   
      }
      return $this;
   }

   /**
    * Determine whether or not the field has a value
    *
    * Note: the default value is ignored
    * @return boolean
    */
   public function hasValue()
   {
      return ($this->value !== null);
   }

   /**
    * Get a single value
    * 
    * @param integer $index
    * @return number|string|null
    */
   public function getValue($index = 0)
   {
      if ($index === 0 && $this->value === null) {
         return $this->defaultValue;
      }

      return (is_array($this->value) && array_key_exists($index, $this->value))
         ? $this->value[$index]
         : null;
   }

   /**
    * Get all of the current values
    * 
    * If no value is currently set, the default value will be returned
    * @return array.<string|integer|float|boolean|null>
    */
   private function getValuesAsArray()
   {
      return ($this->value !== null) ? $this->value : [ $this->defaultValue ];
   }

   /**
    * Get the number of values in the field (ignore the initial null)
    * 
    * @return integer
    */
   public function getValueCount()
   {
      return (is_array($this->value)) ? count($this->value) : 0;
   }

   /**
    * Export the current value(s)
    *
    * note: when exporting from a collection (collection->getValues()), this
    * method is not called if `$this->exportHandler === Field::EXPORT_SKIP`
    * @return array.<number|string>|number|string
    */
   public function exportValue()
   {
      if ($this->exportHandler === self::EXPORT_SKIP) {
         throw new Exception(
            "failed to export value for field with export handler set to ".
            "sndsgd\field\Field::EXPORT_SKIP"
         );
      }

      $values = $this->getValuesAsArray();

      if ($this->exportHandler === self::EXPORT_NORMAL) {
         return (count($values) === 1) ? $values[0] : $values;
      }
      else if ($this->exportHandler === self::EXPORT_ARRAY) {
         return $values;
      }
      else {
         return call_user_func($this->exportHandler, $values);
      }
   }

   /**
    * Add one or more validation rules to the field
    * 
    * @param sndsgd\field\Rule,... $rule 
    * @return sndsgd\Field The field instance
    */
   public function addRules($rule)
   {
      foreach (func_get_args() as $rule) {
         if (!($rule instanceof Rule)) {
            throw new InvalidArgumentException(
               "invalid value provided for 'rule'; ".
               "expecting one or more instances of sndsgd\\field\\Rule"
            );
         }

         $ruleClass = $rule->getClass();
         if ($this->hasRule($ruleClass) === true) {
            throw new Exception(
               "failed to add rule; the field {$this->name} already has an ".
               "instance of '$ruleClass'"
            );
         }

         # the required rule should always be eval'd first
         else if ($ruleClass === Rule::REQUIRED) {
            $this->rules = [$ruleClass => $rule] + $this->rules;
         }
         else {
            $this->rules[$ruleClass] = $rule;
         }
      }
      return $this;  
   }

   /**
    * Determine whether or not the field has a particular rule
    * 
    * @param string $ruleName The name of the rule
    * @return boolean
    */
   public function hasRule($ruleName)
   {
      return array_key_exists($ruleName, $this->rules);
   }

   /**
    * Get all the rules
    *
    * @return array.<string,sndsgd\field\Rule|callable>
    */
   public function getRules()
   {
      return $this->rules;
   }

   /**
    * Validate all the values in the field
    * 
    * @param sndsgd\field\Collection|null $collection A field collection
    * @return integer The number of validation errors encountered
    */
   public function validate(Collection $collection = null)
   {
      $ret = 0;
      $values = $this->getValuesAsArray();
      $len = count($values);

      for ($i=0; $i<$len; $i++) {
         $value = $values[$i];

         # skip fields with a null value if they are not required
         if ($value === null && $this->hasRule(Rule::REQUIRED) === false) {
            continue;
         }

         foreach ($this->rules as $name => $rule) {
            $result = $rule->validate($value, $this->name, $i, $collection);
            if ($result instanceof ValidationError) {
               if ($collection !== null) {
                  $collection->addValidationError($result);   
               }
               $ret++;
               break;
            }

            # if the validation method returned a new value, update it
            if ($result !== $value) {
               $value = $result;
               $this->setValue($result, $i);
            }
         }
      }
      return $ret;
   }
}

