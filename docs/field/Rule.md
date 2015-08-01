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



## Closure Rules

If you encounter the need to create a custom rule and don't see the need to subclass `sndsgd\field\Rule`, you can use `sndsgd\field\rule\Closure`. The handler function will be bound to the associated field immediately before `validate()` is called.

*Example: create a rule that verifies a value is greater than the value before it in the same field.*

```php
/**
 * @this \sndsgd\Field
 * @return boolean Whether or not the value was valid
 */
$handler = function() {
   if (
      $this->index > 0 && 
      ($previousValue = $this->field->getValue($this->index - 1)) && 
      $this->value <= $previousValue
   ) {
      $this->message = "each successive value must be greater than the last";
      return false;
   }
   return true;
};

$rule = new \sndsgd\field\rule\ClosureRule($handler);
```

