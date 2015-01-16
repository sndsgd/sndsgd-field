# sndsgd\field\Collection

Instances of `sndsgd\field\Collection` are use for working with multiple instances of `sndsgd\Field`.



## Example

```php
use \sndsgd\Field;
use \sndsgd\field\StringField;
use \sndsgd\field\Collection;
use \sndsgd\field\Error;
use \sndsgd\field\rule\ClosureRule;
use \sndsgd\field\rule\RequiredRule;
use \sndsgd\field\rule\MinLengthRule;
use \sndsgd\field\rule\MaxValueCountRule;

$coll = new Collection([
   (new StringField('username'))
      ->addRules([
         new RequiredRule,
         new MaxValueCountRule(1),
         new MinLengthRule(3)
      ]),
   (new StringField('password'))
      ->addRules([
         new RequiredRule,
         new MaxValueCountRule(1),
         new MinLengthRule(8)
      ]),
   (new StringField('password_confirm'))
      ->setExportHandler(Field::EXPORT_SKIP)
      ->addRules([
         new RequiredRule,
         new MaxValueCountRule(1),
         new ClosureRule(function($value, $index, $field, $coll) {
            return $value === $coll->exportFieldValue('password');
         })
      ])
]);

$coll->addValues([
   'username' => 'ab',
   'password' => '1234abcd',
   'password_confirm' => 'doesnt-match'
]);

if ($coll->validate()) {
   echo "Success!\n";
   print_r($coll->exportValues());
}
else {
   echo "validation failed:\n";
   foreach ($coll->getErrors() as $error) {
      $name = $error->getName();
      $message = $error->getMessage();
      echo " - '$name' $message\n";
   }
}
```
