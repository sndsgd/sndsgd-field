# sndsgd\field\Collection

Instances of `sndsgd\field\Collection` are use for working with multiple instances of `sndsgd\Field`.

## Example

```php
use \sndsgd\Field;
use \sndsgd\field\Collection;
use \sndsgd\field\ValidationError;
use \sndsgd\field\rule\Closure as ClosureRule;
use \sndsgd\field\rule\Required as RequiredRule;
use \sndsgd\field\rule\MinLength as MinLengthRule;
use \sndsgd\field\rule\MaxValueCount as MaxValueCountRule;

$coll = new Collection([
   Field::string('username')
      ->addRules(
         new RequiredRule,
         new MaxValueCountRule(1),
         new MinLengthRule(3)
      ),
   Field::string('password')
      ->addRules(
         new RequiredRule,
         new MaxValueCountRule(1),
         new MinLengthRule(8)
      ),
   Field::string('password_confirm')
      ->setExportHandler(Field::EXPORT_SKIP)
      ->addRules(
         new RequiredRule,
         new MaxValueCountRule(1),
         new ClosureRule(function($value, $data, $name, $index, $coll) {
            $password = $coll->exportFieldValue('password');
            if ($password !== $value) {
               return new ValidationError(
                  "passwords don't match", 
                  $value,
                  'password', 
                  $index
               );
            }
            return $value;
         })
      )
]);

$coll->addValues([
   'username' => 'ab',
   'password' => '1234abcd',
   'password_confirm' => 'nope'
]);

if ($coll->validate() === true) {
   echo "Success!\n";
   print_r($coll->exportValues());
}
else {
   echo "validation failed:\n";
   foreach ($coll->getValidationErrors() as $error) {
      $name = $error->getName();
      $message = $error->getMessage();
      echo " - '$name' $message\n";
   }
}
```
