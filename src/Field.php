<?php

namespace sndsgd;

use \Exception;
use \InvalidArgumentException;
use \sndsgd\Arr;
use \sndsgd\field\Collection;
use \sndsgd\field\Rule;
use \sndsgd\field\exeception\DuplicateRuleException;
use \sndsgd\field\Error;


/**
 * A container for one or more values
 *
 * @todo Allow for setting an array of default values (Field::EXPORT_ARRAY)
 */
class Field implements \Countable
{
    use \sndsgd\event\Target, \sndsgd\DataTrait;

    // available export types
    const EXPORT_NORMAL = 0;
    const EXPORT_ARRAY = 1;
    const EXPORT_SKIP = 2;

    // visibility options
    const VISIBILITY_PRIVATE = 1;
    const VISIBILITY_PUBLIC = 2;

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
     * @var array<string,boolean>
     */
    protected $aliases = [];

    /**
     * The visibility definition for the field
     *
     * @var integer
     */
    protected $visibility = 2;

    /**
     * A description of the field
     *
     * Note: used to auto-generate help
     * @var string
     */
    protected $description = "no description provided";

    /**
     * A default value to use if no values are added
     *
     * @var string|integer|float|null
     */
    protected $defaultValue = null;

    /**
     * The value(s) are stored in an indexed array
     *
     * When the field has a value, this will always be an array of values
     * @var array<string|integer|float|boolean>|null
     */
    protected $value = null;

    /**
     * Validation rules
     *
     * @var array<string,\sndsgd\field\Rule>
     */
    protected $rules = [];

    /**
     * Validation errors
     *
     * @var array<\sndsgd\field\Errors>
     */
    protected $errors = [];

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
        if (!is_string($name)) {
            throw new InvalidArgumentException(
                "invalid value provided for 'name'; expecting a string"
            );
        }
        $this->name = $name;
    }

    /**
     * Get the number of values in the field
     *
     * @see http://php.net/manual/en/countable.count.php
     * @return int
     */
    public function count()
    {
        return ($this->value === null) ? 0 : count($this->value);
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
     * @return \sndsgd\Field
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
     * @return array<string>
     */
    public function getAliases()
    {
        return array_keys($this->aliases);
    }

    /**
     * @param integer $something
     */
    public function setVisibility($visibility)
    {
        $this->visibility = $visibility;
        return $this;
    }

    /**
     * @return integer
     */
    public function getVisibility()
    {
        return $this->visibility;
    }

    /**
     * Set a human readable description describing the field
     *
     * @param string $description
     * @return \sndsgd\Field
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
     * @return \sndsgd\Field
     * @throws \InvalidArgumentException If $value is not the appropriate type
     */
    public function setDefault($value)
    {
        $this->defaultValue = $value;
        return $this;
    }

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
     * Set the method by which to export the value
     *
     * @param integer|callable $type
     * @return \sndsgd\Field
     * @throws InvalidArgumentException If $type is not valid
     */
    public function setExportHandler($type)
    {
        if (
            $type === self::EXPORT_NORMAL ||
            $type === self::EXPORT_ARRAY ||
            $type === self::EXPORT_SKIP ||
            is_callable($type)
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
     * @param string|integer|null $index The value key in the values array
     * @return \sndsgd\Field
     */
    public function addValue($value, $index = null)
    {
        if ($this->value === null) {
            $this->value = [];
        }

        if ($index === null) {
            $this->value[] = $value;
        }
        else {
            $this->value[$index] = $value;
        }

        return $this;
    }

    /**
     * Set one or all values
     *
     * @param string|integer|float|array<string|integer|float> $value
     * @param integer|null $index
     * @return \sndsgd\Field
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
     * Get all of the current values as an array of values
     *
     * If no value is currently set, the default value will be returned
     * @return array<string|integer|float|boolean|null>
     */
    private function getValuesAsArray()
    {
        if ($this->value === null) {
            return is_array($this->defaultValue) 
                ? $this->defaultValue 
                : [ $this->defaultValue ];
        }
        return $this->value;
    }

    /**
     * Export the current value(s)
     *
     * note: when exporting from a collection (collection->getValues()), this
     * method is not called if `$this->exportHandler === Field::EXPORT_SKIP`
     * @param integer|null $exportHandler A handler to override the 
     * @return array<number|string>|number|string
     */
    public function exportValue($exportHandler = null)
    {
        $exportHandler = $exportHandler ?: $this->exportHandler;

        if (
            $exportHandler === self::EXPORT_NORMAL ||
            $exportHandler === self::EXPORT_SKIP
        ) {
            $values = $this->getValuesAsArray();
            return (count($values) === 1) ? $values[0] : $values;
        }
        else if ($exportHandler === self::EXPORT_ARRAY) {
            if ($this->value === null) {
                return ($this->defaultValue === null) ? [] : $this->defaultValue;
            }
            return Arr::cast($this->value);
        }
        else {
            return call_user_func($exportHandler, $this->getValuesAsArray());
        }
    }

    /**
     * Add a validation rule to the field
     *
     * @param \sndsgd\field\Rule $rule
     * @return \sndsgd\Field The field instance
     */
    public function addRule(Rule $rule)
    {
        $classname = $rule->getClass();
        if ($this->hasRule($classname) === true) {
            throw new Exception(
                "failed to add rule; the field {$this->name} already has an ".
                "instance of '$classname'"
            );
        }
        else if ($classname === Rule::REQUIRED) {
            $this->rules = [$classname => $rule] + $this->rules;
        }
        else {
            $this->rules[$classname] = $rule;
        }
        return $this;
    }

    /**
     * Add validation rules to the field
     *
     * @param array<sndsgd\field\Rule> $rules
     * @return \sndsgd\Field The field instance
     */
    public function addRules(array $rules)
    {
        foreach ($rules as $rule) {
            $this->addRule($rule);
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
     * @return array<string,\sndsgd\field\Rule|callable>
     */
    public function getRules()
    {
        return $this->rules;
    }

    /**
     * Validate all the values in the field
     *
     * @param \sndsgd\field\Collection|null $collection A field collection
     * @return boolean True if all values are
     */
    public function validate(Collection $collection = null)
    {
        $this->errors = [];
        foreach ($this->getValuesAsArray() as $index => $value) {
            # skip fields with a null value if they are not required
            if ($value === null && $this->hasRule(Rule::REQUIRED) === false) {
                continue;
            }

            foreach ($this->rules as $name => $rule) {
                $rule->setValue($value);
                $rule->setField($this, $index);
                $rule->setCollection($collection);
                if ($rule->validate() === true) {
                    $fmtValue = $rule->getValue();
                    if ($fmtValue !== $value) {
                        $value = $fmtValue;
                        $this->setValue($fmtValue, $index);
                    }
                }
                else {
                    $this->errors[] = $rule->getError();
                    break;
                }
            }
        }
        return count($this->errors) === 0;
    }

    /**
     * @return array<\sndsgd\field\Error>
     */
    public function getErrors()
    {
        return $this->errors;
    }
}
