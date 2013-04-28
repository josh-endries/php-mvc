PHP MVC
=======

PHP MVC is a simple, lightweight, PHP-based MVC framework.



Licensing
---------

This module is distributed under a 3-clause BSD license, which you can read in the LICENSE.txt file.



Description
-----------

PHP MVC is a simple, lightweight, PHP-based MVC framework.



Requirements
------------

This framework has a couple relatively simple requirements:

* Sessions are enabled.
* Sessions contain a "messages" Array that is used for displaying messages (via Message objects) to the user.
* If using the dispatcher, the classes must be loaded before calling Dispatch::service().


Usage
-----

To use: include autoload.php. This will include the other files and do any necessary preparation.

MVC contains base classes for two of the three MVC components:

* Controller
	* BaseController implements the basic functionality of a Controller but returns an instance of EmptyView.
* View
	* ReusableView implements a view that can be used multiple times in the same context (e.g.: page). For example, a series of contact forms for different types of addresses.
	* SingularView implements a view that can be used only once in the same context (e.g.: page).

There is no base class for the Model component.

The Dispatch class creates an instance of the appropriate controller and calls its service() method.

	

Contact
-------

https://www.endries.org/josh/contact