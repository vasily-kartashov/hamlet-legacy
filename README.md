Foundation
====================

Very simple PHP framework that is modelled as closely to HTTP specification as possible

Introduction
--------------------
The list of core abstractions is quite short
* Request - Data that either identifies the user (for example cookie, session, IP) or the data entered by the user (for example POST, GET variables).
See it as an ugly object that is required to make the rest of the application code free from site effects
* Resource - Pretty much what the REST specification has in mind when talking about resources.
* Response - HTTP response including headers, session objects, and the payload
* Entity - Sometimes optional payload of the response
* Template - The wrapper objects that connects the template data with the template markup
* Application - Main object that finds the requested resource given user's request

The additional helpful abstractions are
* Locale - Localization object
* Environment - Server environment where the application is deployed right now

The framework requires Memcached PHP extension to work.

Directory structure
--------------------
* library/{scope}/application - Application code
* library/{scope}/core - Core of the framework that is required during runtime
* library/{scope}/vendors - Vendors code that that is required during runtime
* public - Public directory which is DocumentRoot in apache
* utils - Scripts that are used during development, deployment or integration
* library/build - Code that us used during development, deployment or integration
* library/runtime - Runtime code
* library/test - Test code

The number of scopes (library/{scope}) is not limited.

Class loader
--------------------
The framework doesn't use any conventions for namespace to file mapping. Instead you have to generate the class loader
by executing php utils/create-library-loader/run.php

Example application
--------------------
The master branch contains a simple application for documentation purposes. The application is a simple todo list, with
localizations, smarty templates, restful services and different environments.


Typescript
=====================

Debugging in PHPStorm
---------------------
Install the PHPStorm LiveEdit extension
Preferences -> Plugins -> Install JetBrains Plugin.
Search for LiveEdit.

Setup the remote mapping using a deployment configuration
Tools -> Deployment -> Configurations
Create a new 'In place' configuration and enter your webserver url. Go to the 'Mappings' tab and map your local
filesystem public directory to your webserver root.
IMPORTANT - make sure you make it your default configuration by clicking teh server with teh green tick.
Create a new javascript debug configuration.
Run -> Edit Configurations
Set the url to your server url

DOMinatrix
=====================
* The usual initialization sequence is bottom to top (toward the root of the tree) through constructors and than back
  from the root to the top of the tree through init methods
* The constructor signatures in the template and TypeScript files should match otherwise the container will compliant.

ToDo
=====================
* Fix the TypeScript compilation so that the map & js files are not commited
* Add watchers for Vagrant box
* Remove js / css files after they have been merged on the preview task (see that it's not working on dev)

