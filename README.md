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
- [Aye Aye's Behat Feature Context][aye-aye-behat] - For behavioural driven development and testing
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

Next, in two seperate steps we'll grab the packages we'll need for our service to run and the packages we'll use for
development:

```bash
composer require ayeaye/api doctrine/orm lcobucci/jwt
composer require --dev ayeaye/behat-feature-context phpunit/phpunit squizlabs/php_codesniffer phpmd/phpmd
```

Next, lets configure our quality and testing utilities.

We're going to put our source code in a director called `src`, our tests in a directory called `tests` and our public 
files in a directory called `public`. PHPUnit, PHPCS and PHPMD are all configured using xml files. You can configure
them however you like, but here's how I configured mine:

#### PHPUnit

You'll notice that I work with all "strict" flags set to true. This will mean a bit of extra work but I personally 
thing it's worth it as it will mean each test only covers a single unit (unless we specify otherwise).

**phpunit.xml.dist***

```xml
<?xml version="1.0" encoding="UTF-8" ?>
<phpunit bootstrap="vendor/autoload.php"
         colors="true"
         convertErrorsToExceptions="true"
         convertNoticesToExceptions="true"
         convertWarningsToExceptions="true"
         beStrictAboutTestsThatDoNotTestAnything="true"
         checkForUnintentionallyCoveredCode="true"
         beStrictAboutOutputDuringTests="true"
         beStrictAboutTestSize="true"
         beStrictAboutChangesToGlobalState="true"
         verbose="true"
        >
    <testsuites>
        <testsuite name="Tests">
            <directory>./tests</directory>
        </testsuite>
    </testsuites>
    <filter>
        <whitelist processUncoveredFilesFromWhitelist="true">
            <directory suffix=".php">src</directory>
        </whitelist>
    </filter>
</phpunit>
```

#### PHPCS

I follow the PSR-1 and PSR-2 FIG standards, you can configure this with whatever standard you use.

**phpcs.xml**

```xml
<?xml version="1.0"?>
<ruleset name="Rules for PHPCS">
    <description>
        Checks the code style quality of the code
    </description>

    <file>./src</file>
    <file>./tests</file>

    <rule ref="PSR1"/>
    <rule ref="PSR2"/>
</ruleset>
```

#### PHPMD

PHPMD is a big meany... but it gets the job done. We're turning on all the rules, and for now, no exceptions.

**phpmd.xml**

```xml
<?xml version="1.0"?>
<ruleset name="Rules for PHPMD"
         xmlns="http://pmd.sf.net/ruleset/1.0.0"
         xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
         xsi:schemaLocation="http://pmd.sf.net/ruleset/1.0.0
                     http://pmd.sf.net/ruleset_xml_schema.xsd"
         xsi:noNamespaceSchemaLocation="
                     http://pmd.sf.net/ruleset_xml_schema.xsd">
    <description>
        Checks the logical quality of the code
    </description>

    <!-- Import the entire unused code rule set -->
    <rule ref="rulesets/cleancode.xml" />
    <rule ref="rulesets/codesize.xml" />
    <rule ref="rulesets/controversial.xml" />
    <rule ref="rulesets/design.xml" />
    <rule ref="rulesets/naming.xml" />
    <rule ref="rulesets/unusedcode.xml" />
</ruleset>
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
