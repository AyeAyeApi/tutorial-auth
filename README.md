Aye Aye Tutorial - Authentication Service
=========================================

This tutorial will step you through how to create an Authentication Service with Aye Aye Api. The tutorial is broken
into two parts. [Part 1][part-1] we will build simple registration and password checking end points. [Part 2][part-2]
we will build an OAuth Provider that will allow other applications to log in on behalf of users with reduced privileges.

Requirements
------------

To follow this demo, you will need:
- [PHP 5.5][php] or greater
- [Composer][composer]

Part 1 - Registration and Login
-------------------------------

(Work in progress)

Let's start by creating a new project. We're going to need the following packages:
- [Aye Aye Api][aye-aye-api] - The framework for the API
- [Doctrine ORM][doctrine-orm] - To provide a database abstraction layer
- [Luís Cobucci's JWT Library][jwt] - To provide an authentication token

We're also going to plan and test our work with Behat:
- [Aye Aye's Behat Feature Context][aye-aye-behat] - For behavioural driven development and testing

> Side Note: I also recommend the following tools. We won't go into them for the purpose of this tutorial, but examples
exist in the tutorial repository:
- [PHPUnit][phpunit] - For test driven development
- [Squizlabs PHPCS][phpcs] - Code sniffer for conforming to standards (we use PSR-1 and PSR-2)
- [PHPMD][phpmd] - Mess detection tool for telling us if our code is too complex 

That's a lot of tools! But don't worry, a lot of it is quality control and planning, and once that's set up we don't
need to worry so much about it.

### Step 1 - Setting up

Let's get started. We'll begin with just a blank project. From the command line, run:

```bash
composer init -n --name=[package name]
```

Swap `[package name]` with whatever you want to call your project. You can additionally add in a license and author 
here. For example, I used: 

```bash
composer init -n --name=ayeaye/auth --license=MIT --author="Daniel Mason <daniel@ayeayeapi.com>"
```

Next, in two separate steps we'll grab the packages we'll need for our service to run and the packages we'll use for
development:

```bash
composer require ayeaye/api doctrine/orm lcobucci/jwt
composer require --dev ayeaye/behat-feature-context
```

Next, lets configure our quality and testing utilities.

We're going to put our source code in a director called `src`, our tests in a directory called `tests` and our public 
files in a directory called `public`.

#### Initial Controller

We'll start by creating an empty controller. This is just enough to get a 200 response. 

**src/Api/Version1.php**

```php
<?php

namespace AyeAye\Auth\Api;

use AyeAye\Api\Controller;

/**
 * Class Version1
 * @package AyeAye\Auth
 */
class Version1 extends Controller
{

}
```

We've called the controller Version1. At the time of writing, there is no single "best practice" for versioning API's,
just a number of not-very-good practices. Using the url method of versioning (i.e. `/v1`, `/v2`) is not considered
RESTful as it no longer refers to the resource, however it is the easiest to implement, the easiest to understand, and 
Aye Aye automatically makes it discoverable. We'll talk more about that later. If you'd like to read about the other
ways to do it, this is a great article: [Your API versioning is wrong][api-versioning]

In order to autoload this file in the future, we need to tell composer about it. You'll need to open your 
`composer.json` and add the following:

```json
{
    ...
    "autoload": {
        "psr-4": {"AyeAye\\Auth\\": "src/"}
    }
}
```

Once you've done that, run `composer dump-autoload` to regenerate the autoloader.

#### Entry Point

Now that we have our initial controller, we need to tell Aye Aye about it. Users will access the API through a simple
publicly accessible index file:

**public/index.php**

```php
<?php

require_once '../vendor/autoload.php';

use AyeAye\Auth\Api\Version1;
use AyeAye\Api\Api;

$initialController = new Version1();
$api = new Api($initialController);
$api->go()->respond();
```

We can test this worked using PHP's built in server. From our project root:

```bash
php -S localhost:8000 -t public & # Start PHP's server, point it at out public docroot, run it in the background
curl localhost:8000               # Curl the server, it should return {"data":{"controllers":[],"endpoints":[]}}
fg                                # Bring the server back to the foreground, you can now close it with Ctrl+C
```

Part 2 - OAuth Provider
-----------------------

(Work in progress)


[part-1]: #part-1-registration-and-login
[part-2]: #part-1-oauth-provider

[php]: https://secure.php.net/
[composer]: https://getcomposer.org/ 

[aye-aye-api]: https://github.com/ayeayeapi/api
[doctrine-orm]: https://github.com/doctrine/doctrine2
[jwt]: https://github.com/lcobucci/jwt
[aye-aye-behat]: https://github.com/AyeAyeApi/behat-feature-context

[phpunit]: https://github.com/sebastianbergmann/phpunit
[phpcs]: https://github.com/squizlabs/PHP_CodeSniffer
[phpmd]: https://github.com/phpmd/phpmd

[api-versioning]: https://www.troyhunt.com/your-api-versioning-is-wrong-which-is/
