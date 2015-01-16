# sndsgd\field\Rule

Instances of `sndsgd\field\Rule` are reusable objects used to validate or format values in an instance of `sndsgd\Field`.



## Usage

The minimal steps for using a rule are creating a new instance, setting a value, and then validating.

> Note: You should never actually have to perform these steps in this section; the validation process for a field, or a collection is handled by calling `validate` on the relevant instance.

```php
$rule = new \sndsgd\field\rule\IntegerRule;
$rule->setValue('abc');
var_dump($rule->validate());
// => bool(false)
```

You can retrieve an instance of `sndsgd\field\Error` from a rule by calling `getError()` from the rule instance.

```php
$rule = new \sndsgd\field\rule\IntegerRule;
$rule->setValue('abc');
if (!$rule->validate()) {
   $error = $rule->getError();
   var_dump($error->getMessage());
   // => string(18) "must be an integer"
}
```

You can set a custom error message by calling `setMessage` on any instance of `sndsgd\field\Rule`.

```php
$rule = new \sndsgd\field\rule\IntegerRule;
$rule->setMessage('integers only!');
```

> Note: some rules allow for `{{value}}` templates within the message strings; see the source for `sndsgd\field\rule\MinLengthRule` for an example.



## Comparison values

Some rules, such as `sndsgd\field\rule\MinValueRule`, require a value for comparison. In most cases, that value can be set when calling the rule constructor.

```php
$rule = new \sndsgd\field\rule\MinValueRule(42);
$rule->setValue(41);
var_dump($rule->validate());
// => bool(false)
$rule->setValue(42);
var_dump($rule->validate());
// => bool(true)
```



## Using closures

If you encounter the need to create a custom rule and don't see the need to subclass `sndsgd\field\Rule`, you can use `sndsgd\field\rule\Closure`.

*Example: create a validation rule that verifies a value is greater than previous values in the same field.*

```php
/**
 * Signature for a closure rule handler
 * @param mixed $value The value to validate
 * @param integer|null $index The index of the value in it's parent field
 * @param sndsgd\Field|null $field The value's parent field
 * @param sndsgd\field\Collection|null $coll The value's parent collection
 * @return mixed
 * @return boolean Whether or not the value was valid
 * @return array
 *  [0] boolean Whether or not the value was valid
 *  [1] mixed A formatted value to replace the original value
 */
$handler = function(
   $value, 
   $index = null, 
   \sndsgd\Field $field = null, 
   \sndsgd\field\Collection $coll = null
) {
   if (
      $index > 0 && 
      ($previousValue = $field->getValue($index - 1)) && 
      $value <= $previousValue
   ) {
      return false;
   }
   return true;
};

$rule = new \sndsgd\field\rule\ClosureRule($handler);
```

