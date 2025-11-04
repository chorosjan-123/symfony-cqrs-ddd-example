# Description
This example project should be used as showcase implementation of the CQRS / DDD / Symfony Messenger boilerplate in the simplest usable form, introducing REST API with basic Doctrine CRUD.

# Installation
## Requirements & Compatibility
- `PHP 8.4.13^`
- `PHP pdo_sqlite`
- Tested on `Symfony 7.3`

## Installation process
This project is compatible with any and all methods to setup Symfony, including LAMP, WAMP, Docker installations.
1. Follow guide of the chosen installation method
2. Copy `.env.dev` as `.env` or create new one
3. Install [Composer](https://getcomposer.org/)
4. Run `composer install`

# How to run
This application is supplied with CRUD endpoints for the REST API which are as follows

| Method        | Path                          | Description                                       | JSON Body |
| ------------- |-------------------------------|---------------------------------------------------|-----------|
| POST          | /process                      | Create a process                                  | { "title": "Example Title", "description": "Example description", "status": "todo" }
| PATCH         | /process/{id}                 | Update process details                            | { "title": "Example Title New", "description": "Example Desc New", "status": "in_progress" }
| PATCH         | /process/{id}/status          | Change process status                             | { "status": "todo" }
| DELETE        | /process/{id}                 | Delete a process (if allowed)                     |
| GET           | /process                      | Retrieve all processes                            |
| GET           | /process?statusFilter={val}   | Retrieve all processes with given filter          |
| GET           | /process/{id}                 | Retrieve a single process                         |
| GET           | /events                       | Retrieve events                                   |

### It also introduces two showcase rules
* Process can only be deleted if its status is not `done`
* Process can only be marked as `done` if its status was `in_progress`

# Features

## DDD & Hexagonal architecture
[Wikipedia article](https://en.wikipedia.org/wiki/Domain-driven_design)

Layers introduced
* Application layer `App\ProcessFeature\Application`
    * Command
    * Handler
    * Query
* Domain layer `App\ProcessFeature\Domain`
    * Entity
    * Repository (Interface)
    * ValueObject (Enum)
* Infrastucture layer `App\ProcessFeature\Infrastucture`
    * EventListener
    * Exception
    * Repository
* Presentation layer `App\ProcessFeature\Presentation`
    * Controller
    * Request
        * ArgumentResolver
        * DTO

## CQRS
[Wikipedia article](https://en.wikipedia.org/wiki/Command_Query_Responsibility_Segregation)

* commands `App\ProcessFeature\Application\Command`
* queries `App\ProcessFeature\Application\Query`
* handlers `App\ProcessFeature\Application\Handler`

### Symfony Messenger
[Documentation](https://symfony.com/doc/current/messenger.html)

Symfony Messenger allows to send and resolve both synchronous and asynchronous "messages" through the system depemding on its configuration.

### DTO
[Documentation](https://symfony.com/blog/new-in-symfony-6-3-mapping-request-data-to-typed-objects)

Usage of DTO allows to filter out all unnecessary parts of the request before it is resolved in the application controller while providing valitation and predictable structure.


## Symfony Event Listener
[Documentation](https://symfony.com/doc/current/event_dispatcher.html)

Event listeners allow to execute part of a code when an event is triggered in the application.

* `App\ProcessFeature\Infrastructure\EventListener\Doctrine\` ProcessEventListener
    * postPersist - Creates Event record when Process is created 
    * postUpdate - Creates Event record when Process is updated 
    * preRemove - Creates Event record when Process is deleted 
* `App\ProcessFeature\Infrastructure\EventListener\Doctrine\` ProcessProtectionListener
    * preRemove - Throws an Exception if Process should not be deleted

## Symfony Argument Resolver
[Documentation](https://symfony.com/doc/current/controller/value_resolver.html)

Used to filter out and validate arguments of the URI to determine actions like filtering.
* `App\ProcessFeature\Presentation\Request\ArgumentResolver` StatusFilterResolver

## Testing
[Documentation](https://symfony.com/doc/current/testing.html)

Testing is done with use of PHPUnit packages and Symfony Testing framework.

### SQLite
[Documentation](https://symfony.com/doc/current/testing/database.html)

Provided in the project settings allow tests to be run in memory with usage of SQLite.

### Dama modules
[Github page](https://github.com/dmaicher/doctrine-test-bundle)

Dama testing modules are used to make the process of tests in memory to run faster.
