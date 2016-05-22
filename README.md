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

### Creating the database

For the purpose of this demo, we're going to use sqlite. We'll make a little factory class to do the configuration for
us.

**src/Database/Factory.php**

```php
<?php

namespace AyeAye\Auth\Database;

use Doctrine\ORM\ORMException;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\Setup;

/**
 * Class Factory
 * @package AyeAye\Auth\Database
 */
class Factory
{

    /**
     * @var EntityManager
     */
    protected static $entityManager;

    /**
     * This method can be used to inject a mock EntityManager for testing.
     * @param EntityManager $entityManager
     */
    public static function setEntityManager(EntityManager $entityManager = null)
    {
        static::$entityManager = $entityManager;
    }

    /**
     * @throws ORMException
     * @return EntityManager
     */
    public static function getEntityManager()
    {
        if (!static::$entityManager) {
            // Set up the Database Entities
            $paths = [ __DIR__ . "/Entity" ];
            $isDevMode = false;
            $config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode, null, null, false);

            // Create the Entity Manager
            $sqliteFile = __DIR__.'/../../db.sqlite';
            $dbParams = [
                'driver'   => 'pdo_sqlite',
                'user'     => 'root',
                'password' => '',
                'path'     => $sqliteFile,
            ];
            static::$entityManager = EntityManager::create($dbParams, $config);

            // If the database file doesn't exist, make it
            if(!file_exists($sqliteFile)) {
                touch($sqliteFile);
                $schemaTool = new SchemaTool(static::$entityManager);
                $classes = static::$entityManager->getMetadataFactory()->getAllMetadata();
                $schemaTool->createSchema($classes);
            }
        }
        return static::$entityManager;
    }
}
```

Our entity is pretty simple too.

```php
<?php

namespace AyeAye\Auth\Database\Entity;

use AyeAye\Formatter\Serializable;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class User
 * @package AyeAye\Auth\Database\Entity
 * @ORM\Entity
 * @ORM\Table(name="users")
 */
class User implements Serializable
{

    /**
     * @var integer
     * @ORM\Id
     * @ORM\Column(type="guid")
     * @ORM\GeneratedValue(strategy="UUID")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(type="string",length=255)
     */
    protected $email;

    /**
     * @var string
     * @ORM\Column(type="string",length=255)
     */
    protected $passwordHash;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @param $password
     * @return bool
     */
    public function checkPassword($password)
    {
        return password_verify($password, $this->passwordHash);
    }

    /**
     * @param string $passwordHash
     */
    public function setPassword($passwordHash)
    {
        $this->passwordHash = password_hash($passwordHash, PASSWORD_DEFAULT);
    }

    /**
     * Serialise for Aye Aye Api response
     * @return array
     */
    public function ayeAyeSerialize()
    {
        return [
            'id' => $this->getId(),
            'email' => $this->getEmail()
        ];
    }
}
```

Finally lets add two method to our controller to check this all works. The first will insert a new user:

```php
    /**
     * Insert a new user
     * @param $email
     * @param $password
     * @return string
     */
    public function postUserEndpoint($email, $password)
    {
        $user = new User();
        $user->setEmail($email);
        $user->setPassword($password);

        $entityManager = Factory::getEntityManager();
        $entityManager->persist($user);
        $entityManager->flush();
        return $user->getId();
    }
```

We can check this works with the following curl command


```bash
php -S localhost:8000 -t public & 
curl --data 'email=test@exampl.com&password=test' 127.0.0.1:8000/user
```

You should get a response like this: `{"data":"AD71ED8C-6C56-4AC0-94BE-6B9860C5B8B2"}`

The ID is generated by Doctrine and is preferable to an incrementing number because it's essentially impossible to guess
other id's.
 
The last endpoint is one that will just give us the user details for a given id.

```php
    /**
     * Finds a user by their ID
     * @param string $user The users ID
     * @throws AyeAyeException
     * @return User Returns a user object
     */
    public function getUserEndpoint($user)
    {
        if (!$user) {
            throw new AyeAyeException("A 'user' parameter must be provided", 400);
        }

        $entityManager = Factory::getEntityManager();
        $userObject = $entityManager
            ->getRepository(User::class)
            ->find($user);
        yield 'user' => $userObject;
    }
```

Now we can get our user with the following:

```bash
$ curl "127.0.0.1:8000/user?user=AD71ED8C-6C56-4AC0-94BE-6B9860C5B8B2"
{"user":{"id":"AD71ED8C-6C56-4AC0-94BE-6B9860C5B8B2","email":"test@exampl.com"}}
```

There's several cool things here. Firstly, we didn't tell Aye Aye where to get that parameter, it used the name of the
parameter in the function to find the parameter in the request. Additionally, parameters can be url slugs, if they
follow a slug named the same thing as the parameter. In this case, the `user` endpoint is called the same thing as the
`user parameter` so we can take advantage of that.

```bash
$ curl "127.0.0.1:8000/user/AD71ED8C-6C56-4AC0-94BE-6B9860C5B8B2"
{"user":{"id":"AD71ED8C-6C56-4AC0-94BE-6B9860C5B8B2","email":"test@exampl.com"}}
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
