PHP MVC
=======

PHP MVC is a simple, lightweight, PHP-based MVC framework.



Licensing
---------

This module is distributed under a 3-clause BSD license, which you can read in the LICENSE.txt file.



Description
-----------

PHP MVC is a simple, lightweight, PHP-based MVC framework.



Usage
-----

To use: include autoload.php. This will include the other files and do any necessary preparation.

MVC contains base classes for two of the three MVC components:

* Controller
	* BaseController implements the basic functionality of a Controller but returns an instance of EmptyView.
* View
	* ReusableView implements a view that can be used multiple times in the same context (e.g.: page).
	* SingularView implements a view that can be used only once in the same context (e.g.: page). FOr example, a series of contact forms for different types of addresses.

There is no base class for the Model component.

	

Contact
-------

https://www.endries.org/josh/contact