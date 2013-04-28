<?php
require_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'Controller.php');
require_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'UnknownActionException.php');
require_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'EmptyView.php');

/**
 * The BaseController class implements the basic functionality of a Controller,
 * but only returns an EmptyView. It serves mainly as an example of one way to
 * implement a Controller, and as future-proofing in case shared functionality
 * that should apply to all Controllers is determined in the future.
 * 
 * @author Josh Endries <josh@endries.org>
 * @since 2.0.0
 */
abstract class BaseController implements Controller {
	public function service($name, Array $parameters) {
		/*
		 * Create an EmptyView to return as a last resort.
		 */
		$view = new EmptyView();

		/*
		 * Pass handling along to the correct method based on the action name.
		 */
		switch ($name) {
			default:
				/*
				 * We have no action by that name, so throw the appropriate exception.
				 */
				throw new UnknownActionException($name, __CLASS__.' cannot handle actions with name '.$name);
		}
		
		/*
		 * Return the view.
		 */
		return $view;
	}
}