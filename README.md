# CakePHP Domain Driven Design Controller

Part of the CakePHP Domain Driven Design Building Blocks suite.

Compatible with CakePHP 4.5 and CakePHP 5.

## Key Objectives
- Ease creation of REST(Ful) controller
- Respect Layered architecture. This plugin takes place in the Infrastructure layer.
- Respect CakePHP conventions
- Applies CakePHP Convention over Configuration philosophy

## Setup
You can choose to add the behavior to your existing controller or to extend your existing controller with `RestAppController`.
I would recommend to extend your existing controller.

### Extending your controller
Just extend your controller as usual:
```php
class AppController extends \CakeDDD\Controller\RestAppController {
    ...
}
```
### Load behavior
If you have few REST controllers, you can load the behavior in each of them:
```php
class YourController extends AppController {
    public function initialize(): void
    {
        parent::initialize();

        $this->loadComponent('CakeDDD.RestHandler');
    }
}
```

## Usage
Create a controller, as usual and, in your controller's action, collect your entities and mark them for serialization:
```php
// App\Controller\SomeRestController
public function index(): void {
   /**
    * @var SomeEntity $aggregatesOrEntities
    */
    $aggregatesOrEntities = $someService->getDomainAggregate();
    $this->serialize($aggregatesOrEntities);
}
```

Then create your Response DTO in View folder, respecting CakePHP conventions in a subfolder named `json`, that implements `JsonSerializable`: 
```php
// App\View\SomeRests\json\SomeRestResponseDto
class SomeRestResponseDto implements JsonSerializable {
    public function __construct(
        private readonly SomeEntity $entity // SomeEntity class, is given by SomeRestController::index()
    ){}

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->entity->getId(),
            'someName' => $this->entity->getName()
        ];
    }
}
```