# Validator
Simple validator library for Laravel framework with multiple scenarios. By using this package, you write your validator once
and use every where and moderate your Domain rules easily.

### Installation
Use composer:
```bash
 composer require laratalks/validator
```

### Usage
Your valdiation classes must extends `Laratalks\Valdiator\AbstarctValdiator` :
```php
<?php
#UserValidator.php

namespace YourApp\Validators;

use Laratalks\Validator\AbstractValidator;

class UserValidator extends  AbstractValidator
{

    protected $registrationRules = [
        'name' => ['required'],
        'email' => ['required', 'email'],
        'home_page' => ['required', 'url']
    ];


    protected $activationRules = [
        'id' => ['required', 'exists:users'],
        'token' => ['required', 'min:64']
    ];

    protected $anotherScenarioRules = [
        'key1' => ['rule1', 'rule2'],
        'key2' => ['rule1', 'rule2']
    ];
    
}
```

You must inject validatio in your methods or controller `__construct` method to using it:

```php
<?php
# UserController.php

namespace Laratalks\Validator;

use YourApp\Validators\UserValidator;
use Laratalks\Validator\Exceptions\ValidationException;

class UserController extends Controller
{
    public function register(Request $request, UserValidator $valdiator)
    {
        try {
            // validate user input
            $valdiator
                ->setScenario('registration')
                ->validate($request->all());
            
        } catch (ValidationException $e) {
            // catch errors
            return $e->getErrors();
        }
    }
}
```