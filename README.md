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
* library/application - Application code
* library/core - Core of the framework that is required during runtime
* library/vendors - Vendors code that that is required during runtime
* public - Public directory which is DocumentRoot in apache
* utils - Scripts that are used during development, deployment or integration
* utils/library - Code that us used during development, deployment or integration

Class loader
--------------------



Example application
--------------------
The master branch contains a simple application for documentation purposes. The application is a simple todo list, with
localizations, smarty templates, restful services and different environments.