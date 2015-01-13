# sndsgd\Field

Instances of `sndsgd\Field` are used to store values, validation rules, and perform validation and/or formatting of values. 



## Field types

When creating a field, use the appropriate class for the type of value you are expecting. The following types are included by default, and you can subclass `sndsgd\Field` to create your own.

- ```sndsgd\field\Boolean```
- ```sndsgd\field\Float```
- ```sndsgd\field\Integer```
- ```sndsgd\field\String```



## Creating a field

To create a field, just pass the name of the field to its constructor:

```php
$field = new \sndsgd\field\Integer('id');
```

Fields can also have aliases, which become useful when dealing with a collection of fields. To set an alias, call `addAliases()`:

```php
$field->addAliases('i');
```

For convenience, the process of creating a field with an alias can be completed in a single call to a static method on `sndsgd\Field` named after the field type:

*Example: creating an integer field named 'id' with an alias 'i' using the longhand and shorthand methods:*

```php
$f1 = new \sndsgd\field\Integer('id');
$f1->addAliases('i');
$f2 = \sndsgd\Field::integer('id', 'i');
var_dump($f1 == $f2);
// => bool(true)
```

## Setting & adding values

To set a field's value, call `setValue()`. To add additional values, call `addValue()`.

> Note: calling `setValue()` replaces all values with the new value.

```php
$field->setValue(1);
$field->addValue(2);
```



## Validation/Formatting

All values in a field are validated/formatted when you call `validate()`. If validation fails, an instance of `sndsgd\field\ValidationError` is returned.

Example: validating a field

```php
$result = $field->validate();
if ($result instanceof ValidationError) {
   echo 'Error: '.$result->getMessage().PHP_EOL;
} else {
   // validation successful
}
```



### Rules

Instances of `sndsgd\field\Rule` are used for validating and/or formatting values, and can be added to a field using `addRules()`.

> Note: a field's constructor may add rules to the field when it is created. For example, the `sndsgd\field\Integer` constructor adds `sndsgd\field\rule\Integer` to every instance it creates.

> Note: if you add `sndsgd\field\rule\Required` to a field's list of rules, it will always prepended to the list.

Example: add a rule that ensures a value is at least 1:

```
$field->addRules(new \sndsgd\field\rule\MinValue(1));
```



## Retrieving a value or values

A single value can be retrieved using `getValue()`, and all values can be retrieved using `exportValue()`. To retrieve a particular value, pass the index of the desired value to `getValue()`.

```php
$field->setValue([1,10,100]);
var_dump($field->getValue());
// => int(1)
var_dump($field->getValue(1));
// => int(10)
var_dump($field->getValue(2));
// => int(100)
var_dump($field->exportValue());
// => array(3) { [0] => int(1) [1] => int(10) [2] => int(100) }
```



## Coercing an exported value

During a field's value export, the value(s) can be updated to meet certain requirements. Use `setExportHandler()` to provide a constant or a callback to process the values before `exportValue()` actually returns the value(s).

### Export constants

- `sndsgd\Field::EXPORT_NORMAL` - *default* - Export an array if multiple values exist; otherwise just export the single value.
- `sndsgd\Field::EXPORT_ARRAY` - Forces the exported value to an array or values.
- `sndsgd\Field::EXPORT_SKIP` - *for use with `sndsgd\Field\Collection`* - Ignore the field while exporting a collection of fields.

*Example: Using a constant to ensure the exported value is an array:*

```php
$field->setValue(1);
$field->setExportHandler(\sndsgd\Field::EXPORT_ARRAY);
var_dump($field->exportValue());
// => array(1) { [0] => int(1) }
```

### Export callbacks

```php
/**
 * Field export handler callback signature
 * @param array $values The field values
 * @return mixed 
 */
function(array $values)
```

*Example: ensure exported values are unique:*

```php
$field->setValues([1,2,1]);
$field->setExportHandler('array_unique');
var_dump($field->exportValue());
// => array(2) { [0] => int(1) [1] => int(2) }
```


## Example



```php
<?php

use \sndsgd\Field;
use \sndsgd\field\rule\Required as RequiredRule;
use \sndsgd\field\rule\MinValue as MinValueRule;
use \sndsgd\field\ValidationError;

$_GET = [
   'id' => [1,4,5],
];

$field = Field::integer('id', 'i')
   ->setDescription('The order id for one or more orders to proces')
   ->addRules(
      new RequiredRule(),
      new MinValueRule(1)
   );

$field->setValue($_GET['id']);
$result = $field->validate();
if ($result instanceof ValidationError) {
   $message = $result->getMessage();
   echo "Error: $message\n";
}
else {
   foreach ($field->exportValue() as $id) {
      echo "processing order #{$id}\n";
      // ...
   }
}
```
