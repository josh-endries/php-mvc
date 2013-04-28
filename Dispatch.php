<?php
require_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'UnknownControllerException.php');

/**
 * The Dispatch class implements a process by which an HTTP request URI is
 * split into components and forward processing on to the controller and
 * action specified by those components.
 * 
 * @author Josh Endries <josh@endries.org>
 * @since 1.0.0
 */
class Dispatch {
	/**
	 * A URL to which the client is redirected if there is no other option.
	 */
	private static $errorURL = NULL;
	
	/**
	 * Set a URL to which the client is redirected if there is no other option.
	 * 
	 * @param String $url The URL.
	 */
	public static function setErrorURL($url) {
		self::$errorURL = $url;
	}
	
	/**
	 * The service method scans the request URI and attempts to extract the
	 * controller and action components. If they exist, it attempts to create
	 * a Controller object named the same as that component, and calls the
	 * doAction method for the extracted action.
	 * 
	 * @param String $prefix A URL prefix to strip off (or NULL).
	 * @param String $defaultController The default controller name.
	 * @param String $defaultAction The default action name.
	 * @throws RedirectException from the controller.
	 * @throws UnknownActionException from the controller.
	 * @throws UnknownControllerException if the controller component is found
	 * in the URI but no corresponding class is found.
	 */
	public static function service($prefix, $defaultController, $defaultAction) {
		/*
		 * First things first. Make sure we can use sessions.
		 */
		if (!extension_loaded("session")) throw new Exception("This application requires the session module.");
		
		/*
		 * Okay, at this point we can use sessions. Determine if there is
		 * already an existing session, or if we need to create one.
		 */
		$autoStartSessions = ini_get("session.auto_start");
		if ($autoStartSessions === FALSE) throw new Exception("Session module exists but session.auto_start is unset!");
		if (strcmp($autoStartSessions, "1") === 0) {
			/*
			 * Session auto-start is enabled, we should already have a session
			 * created.
			 */
			if (!isset($_SESSION)) throw new Exception("Session auto-start is enabled but there is no _SESSION variable!");
		} else {
			/*
			 * Session auto-start is disabled, we may need to create a session.
			 */
			if (!isset($_SESSION)) session_start();
		}
		
		/*
		 * We have a session. Create the messages Array to hold user feedback.
		 * If $_SESSION['messages'] already exists, make sure it's an Array. At
		 * that point, we can only hope it will be used as we intend...
		 */
		if (!array_key_exists('messages', $_SESSION)) {
			$_SESSION['messages'] = Array();
		} else if (!is_array($_SESSION['messages'])) {
			throw new Exception("Session contains a non-Array messages element!");
		}
		
		/*
		 * Determine the URI that we're interested in (the controller and
		 * action). This can be tricky as it changes depending on the server
		 * configuration and operating system. What would be ideal is a URI
		 * from the root (/) up to but not including the query string. This is
		 * normally found in the PATH_INFO parameter.
		 * 
		 * Here are some examples:
		 * 
		 * Apache RewriteRule: /blah/(.*) /path/to/dispach.php/$1 [L,NC,QSA]
		 * Request URI: /blah/controller/action?asdf
		 *   [REQUEST_URI] => /blah/controller/action?asdf
		 * 	 [SCRIPT_NAME] => /blah
		 *   [PATH_INFO] => /controller/action
		 * 	 [PHP_SELF] => /blah/controller/action
		 */
		$uri = $_SERVER['PATH_INFO'];
		
		/*
		 * Strip out the file name, if it exists, so we have only the necessary
		 * components when looking for the controller and action.
		 */
		if (!is_null($prefix) && preg_match("|^{$prefix}|", $_SERVER['PATH_INFO']) > 0) {
			$uri = substr($_SERVER['PATH_INFO'], strlen($prefix));
		}

		/*
		 * Initialize the controller and action to NULL. This lets us determine
		 * if we found the intended pair from the URI.
		 */
		$controller = NULL;
		$action = NULL;
		
		/*
		 * Split the request URI into components based on the slashes. The
		 * first component is the controller and the second is the action.
		 * We use PREG_SPLIT_NO_EMPTY to filter out slashes at the beginning
		 * and end of the URI.
		 */
		$path = preg_split('|/|', $uri, -1, PREG_SPLIT_NO_EMPTY);

		/*
		 * Set the action and controller.
		 */
		if (count($path) > 1) {
			/*
			 * Set the controller name we got from the URI. We use lower case
			 * here to avoid problems with case-sensitive class names (e.g.
			 * camel-case names).
			 */
			$potentialControllerName = strtolower($path[0]);
			
			/*
			 * Get an Array containing declared class names as values. The
			 * order begins with built-in PHP classes, so we set this to a
			 * separate variable in order to traverse from the end of the
			 * Array back towards the beginning, which should be faster
			 * than going in the natural order and faster than sorting the
			 * whole Array backwards before traversing in the natural order. 
			 */
			$declaredClasses = get_declared_classes();
			$numDeclaredClasses = count($declaredClasses);
			
			/*
			 * Loop through all declared classes and check if they match our
			 * controller name (with an optional 'Controller' suffix) and if
			 * if implements the Controller interface. Only then is the class
			 * considered a valid controller.
			 */
			for ($i = $numDeclaredClasses - 1; $i >= 0; $i--) {
				/*
				 * Make sure this class implements the Controller interface
				 * first. This will weed out most classes.
				 */
				if (array_key_exists('Controller', class_implements($declaredClasses[$i]))) {
					/*
					 * Set the class name. Again, user lower case to avoid issues.
					 */
					$className = strtolower($declaredClasses[$i]);
				
					/*
					 * Here, we check if the declared class is the correct
					 * class for this URI-extracted class name. This could be
					 * true in a few ways:
					 * 
					 * 1. The URI value and the class name are both singular
					 * or are both plural, e.g. "/car/buy" and "CarController".
					 * 
					 * 2. The URI is plural but the class is singular, e.g.
					 * "/cars/buy" and "CarController".
					 * 
					 * 3. The URI is singular but the class is plural, e.g.
					 * "/car/buy" and "CarsController".
					 */
					if (strcmp($className, $potentialControllerName.'controller') === 0) {
						/*
						 * CarsController == /cars/buy
						 * CarController == /car/buy
						 */
						$controller = $className;
						$action = $path[1];
						break;
					} else if (strcmp($className, $potentialControllerName.'scontroller') === 0) {
						/*
						 * CarsController == /car/buy
						 */
						$controller = $className;
						$action = $path[1];
						break;
					} else if (strrpos($potentialControllerName, 's') == strlen($potentialControllerName)-1 && strcmp(substr($potentialControllerName, 0, -1).'controller', $className) === 0) {
						/*
						 * CarController == /cars/buy
						 */
						$controller = $className;
						$action = $path[1];
						break;
					}
				}
			}
		} else {
			/*
			 * Set the defaults. These are used only if we do not find a controller
			 * and action in the URI (even if the found items are invalid).
			 */
			$controller = $defaultController;
			$action = $defaultAction;
		}
		
		/*
		 * Check that we have what we need. This should only happen if we do
		 * not find a matching URI component and we are passed in NULL for a
		 * default controller.
		 */
		if ($controller == NULL) throw new UnknownControllerException("Could not find a valid controller or the specified controller does not exist.");

		/*
		 * Attempt to instantiate the controller that was found.
		 */
		$controller = new $controller();
		$parameters = array('get' => &$_GET, 'post' => &$_POST);

		/*
		 * We have everything we need. Attempt to process the action. Since this
		 * is a global parent point for all real processing, catch any thrown
		 * Exception and attempt to handle it.
		 */
		$controller->service($action, $parameters)->render();
	}
}
