Sainsbury's Scraper
===================

Result of my test that I was given to complete as per my employment application.

It has been preached to me in PHP Architect that to learn a new framework/library
one should pick a real task as opposed to participating in tutorial's hence I
picked Symfony Console. Read plenty about it, all made sense etc but I could not
say I have used it. I can now, however I can safely say I have only touched the
"tip of the iceburg" as this task did not require me to use it's full capabilities.

Goutte Client is a lightweight scraping library I have come across before but did
not have a use case to use it until now. Under the bonnet it uses guzzle to do the
HTTP request and returns a Symfony crawler object which allows you to transverse
the DOM using jQuery style selectors. Quite neat.

The other I have opted to use is Phake unit testing mocking library. I was introduced 
to it during PHP UK 2016 and I was impressed with what was presented as it seemed to 
reduce the amount of code it takes to mock an object compared to PHP unit's native 
support. This task provided me with the perfect opportunity to experiment with the 
library and it worked well.

Requirements
------------

  * PHP 5.4+
  * Composer

Installation
------------

  1. Git clone this repo
  2. Navigate to root of cloned directory via terminal
  3. Execute `composer install` in terminal
  4. Execute `php vendor/bin/phpunit` for tests
  5. Execute `php app/console sainsburys:products` to execute main application

Enhancements
------------

  * Increase test coverage
  * Add logging
  * Could add response status code checks to finer grain exceptions
  * Define more explicit DOM selectors defined in services
  * Define model size strategy classes rather than callables
  * Add a progress bar to CLI application
  * Finer grain non reachability checks as currently an exception is caught for both
products and individual product scrapes whilst they could be separated.
  * Register the guzzle response in the UnexpectedResponseException so that it can
be logged
  * Define an service data abstraction interface so that the abstraction can be
extended to a web service or database as well as scraping
  * ProductModel can be adopted to use the Prototype design pattern, so that each time
a new instance is required simply clone instead of creating a new instance which
can be expensive
