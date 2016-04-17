Sainsburys
==========

Result of my test that I was given to complete as per my employment application.

It has been preached to me in PHP Architect that to learn a new framework/library
one should pick a real task as opposed to participating in tutorial's hence I
picked Symfony Console. Read plenty about it, all made sense etc but I could not
say I have used it. I can now, however I can safely say I have only touched the
tip of the iceburg as this task did not require me to use it's full capabilities.

Requirements
------------

  * PHP 5.4+
  * Composer

Installation
------------

  1. Git clone this repo
  2. Navigate to root of cloned directory via terminal
  3. Execute `composer install` in terminal
  4. Execute `vendor/bin/phpunit` for tests
  5. Execute `php app/console sainsburys:products` to execute main application

Enhancements
------------

  * Increase test coverage
  * Could add response status code checks to finer grain exceptions
  * Define more explicit dom selectors defined in services
  * Define model size strategy classes rather than callables
  * Add a progress bar to CLI application
