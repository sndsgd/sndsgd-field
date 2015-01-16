# sndsgd\Field

Instances of `sndsgd\Field` are used to store values, rules, and perform validation and/or formatting of values. 



## Field types

The following types are included by default, and you can subclass `sndsgd\Field` to create your own.

- ```sndsgd\field\BooleanField```
- ```sndsgd\field\FloatField```
- ```sndsgd\field\IntegerField```
- ```sndsgd\field\StringField```
- ```sndsgd\field\DateField```



## Creating a field

To create a field, just pass the name of the field to its constructor:

```php
$field = new \sndsgd\field\IntegerField('id');
```

Fields can also have aliases, which become useful when dealing with a collection of fields. To set an alias, call `addAliases()`:

```php
$field->addAliases('i');
```



## Setting & adding values

To set a field's value(s), call `setValue()`. To add additional values, call `addValue()`. 

```php
$field->setValue(1);
$field->addValue(2);
```



## Rules

Instances of `sndsgd\field\Rule` are used for validating and/or formatting field values, and can be added to a field using `addRule()` or `addRules()`.

> Note: a field's constructor may add rules to the field when it is created. For example, the `sndsgd\field\IntegerField` constructor adds `sndsgd\field\rule\IntegerRule` to every instance it creates.

> Note: if you add `sndsgd\field\rule\RequiredRule` to a field's array of rules, it will always prepended to the array.


```
$field->addRule(new \sndsgd\field\rule\MinValue(1));
```



## Validation/Formatting

All values in a field are validated/formatted using its rules when you call `validate()`. If validation fails, you can retrieve an instance of `sndsgd\field\Error` by calling `getError()`.

```php
if ($field->validate() === false) {
   $error = $field->getError();
}
else {
   // the value (or values) are all valid
}
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

