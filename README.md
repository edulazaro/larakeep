# Keepers for Laravel

<p align="center">
    <a href="https://packagist.org/packages/laravel/folio"><img src="https://img.shields.io/packagist/dt/edulazaro/larakeep" alt="Total Downloads"></a>
    <a href="https://packagist.org/packages/laravel/folio"><img src="https://img.shields.io/packagist/v/edulazaro/larakeep" alt="Latest Stable Version"></a>
</p>


## Introduction

Larakeep is a package which allows to create and handle Keepers. Keepers allow to process the value of the desired model fields.

Sometimes, a field value must be configured when creating or updating a model instance. A common place to do this are the observers. This can lead to bloat the observers with repeated code or bloating the the models with functions, resulting in big files.

keepers allow to set the value of the desired fields on separate classes, keeping the code cleaner.

## Actions VS Keepers

While Keepers and Actions share similarities, they serve different purposes in Laravel applications:

| Feature | Actions Pattern | Keepers (Larakeep) |
|---------|---------------|----------------|
| **Purpose** | Perform a self-contained operation (e.g., `CreateUserAction`, `DeletePostAction`) | Maintain or compute model field values dynamically |
| **Scope** | Often used for operations that affect multiple models or require business logic | Specific to maintaining values inside a model |
| **Implementation** | Typically standalone classes invoked as `SomeAction::run($params)` | Assigned to models using attributes or manually (`keep()`) |
| **Examples** | `CreateInvoiceAction`, `SendNotificationAction` | `getFormattedName()`, `computeRankings()` |

- **Use Keepers when:** You need to centralize computed fields, enforce model-based transformations, or derive values dynamically.
- **Use Actions when:** You are performing operations that modify multiple models, involve services, or handle workflow logic.

## How to install Larakeep

Execute this command on the Laravel root project folder:

```bash
composer require edulazaro/larakeep
```

## How to create a Keeper

You can create a Keeper manually or using the `make` command:

```bash
php artisan make:keeper MyModelKeeper
```

The Keeper will be created by default for the model `\App\Models\MyModel`.

You can also specify the model you are creating the Keeper for by using a second argument:

```bash
php artisan make:keeper MyClassKeeper  "\App\Models\Whatever\MyModel"
```

In this case, the keeper will be created for the model `\App\Models\Whatever\MyModel`.

## How to configure a Keeper

After creating a Keeper, you will need to add the `HasKeepers` concern to the referenced model:

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use EduLazaro\Larakeep\Concerns\HasKeepers;

class MyModel extends Model
{
    use HasKeepers;
}
```
### Registering Keepers manually

You can manually assign a Keeper to a model in a service provider's boot method:

```php
namespace App\Providers;

use App\Models\MyModel;
use App\Keepers\MyModelKeeper;

class AppServiceProvider extends Model
{
    // ...
    public function boot()
    {
        // ...
        MyModel::keep(MyModelKeeper::class);
    }
}
```

You can add many Keepers to a single model:

```php
MyModel::keep(MyModelKeeperA::class);
MyModel::keep(MyModelKeeperB::class);
```

### Registering Keepers using Attributes

Alternatively, you can register Keepers using attributes directly in the model:

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use EduLazaro\Larakeep\Concerns\HasKeepers;
use EduLazaro\Larakeep\Attributes\KeptBy;
use App\Keepers\MyModelKeeper;

#[KeptBy(MyModelKeeper::class)]
class MyModel extends Model
{
    use HasKeepers;
}
```

This method allows you to keep the registration clean and self-contained within the model itself, without needing to modify the service provider.

Larakeep supports assigning multiple Keepers to a model using multiple `#[KeptBy]` attributes:

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use EduLazaro\Larakeep\Concerns\HasKeepers;
use EduLazaro\Larakeep\Attributes\KeptBy;
use App\Keepers\MyModelKeeperA;
use App\Keepers\MyModelKeeperB;

#[KeptBy(MyModelKeeperA::class)]
#[KeptBy(MyModelKeeperB::class)]
class MyModel extends Model
{
    use HasKeepers;
}
```

## How to use a Keeper

To use Keeper you need to use the word `get` + the camel case version of the model attribute to process. For example, it's common to keep separate the model name and the search string for the model. In this case a keeper method is handy to set the attribute value:

```php
namespace App\Keepers;

use \App\Models\MyClass;

class MyClassKeeper
{
    // ...
    public function getSearchText()
    {
        return $this->myClass->name . ' ' . $this->myClass->tag;
    }
}
```

The method can also accept parameters, adding the keyword `With` to the method name:


```php
namespace App\Keepers;

use \App\Models\MyClass;

class MyClassKeeper
{
    // ...
    public function getSearchTextWith($whatever)
    {
        return $this->myClass->name . ' ' . $this->myClass->tag;
    }
}
```

Now, on an observer, you can process the `search_text` field to set its value:

```php
$myClassInstance->process('search_text'); // To execute the getSearchText method.
```

Or if the method has parameters:

```php
$myClassInstance->processWith('search_text', 'Any string'); // To execute the getSearchTextWith method.
```


You can also pass an array of fields to process all of them at the same time:

```php
$myClassInstance->process(['search_text', 'word_count']);
```

***The model will still need to be saved.***


## How to add Tasks

You can prepend any other word than`get` to the keeper methods, like `configure`:


```php
namespace App\Keepers;

use \App\Models\MyClass;

class MyClassKeeper
{
    // ...
    public function configureSearchText()
    {
        return $this->myClass->name . ' ' . $this->myClass->tag;
    }
}
```

In the same way, these methods can also accept parameters:


```php
namespace App\Keepers;

use \App\Models\MyClass;

class MyClassKeeper
{
    // ...
    public function configureSearchTextWith($whatever)
    {
        return $this->myClass->name . ' ' . $this->myClass->tag;
    }
}
```

However to process these fields with these methods you will need to use the `processTask` method, with the name you prepended:

```php
$myClassInstance->processTask('configure','search_text');
```

Or if the method has parameters:

```php
$myClassInstance->processTaskWith('configure', 'search_text', 'Any string');
```

You can also pass an array of fields to process all of them at the same time:

```php
$myClassInstance->processTask('configure', ['search_text', 'word_count']);
```

***The model will still need to be saved.***

## License

Larakeep is open-sourced software licensed under the [MIT license](LICENSE.md).