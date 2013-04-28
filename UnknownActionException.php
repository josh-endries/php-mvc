<?php
/**
 * The UnknownActionException is thrown when a Controller is asked to process an
 * action which it cannot handle.
 * 
 * @author Josh Endries <josh@endries.org>
 * @since 2.0.0
 */
class UnknownActionException extends Exception {
	private $name;

	/**
	 * Create a UnknownActionException object.
	 * 
	 * @param String $name The name of the action that was requested.
	 * @param String $message An optional message.
	 */
	public function __construct($name, $message = null) {
		$this->name = $name;
		$this->message = $message;
	}
}