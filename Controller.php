<?php

/**
 * A Controller handles requests from clients.
 * 
 * @author Josh Endries <josh@endries.org>
 * @since 2.0.0
 */
interface Controller {
	/**
	 * The service method will attempt to process the given action.
	 * 
	 * @param String $name The name of the action to process.
	 * @param Array $parameters The request parameters.
	 * @return View The view to render.
	 * @throws UnknownActionException if there is no action by that name.
	 * @throws RedirectException if the request should be redirected.
	 */
	public function service($name, Array $parameters);
}
