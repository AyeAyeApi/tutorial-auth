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
