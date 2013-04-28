<?php
require_once('View.php');

/**
 * The EmptyView class implements a simple view that displays nothing to the
 * client.
 * 
 * @author Josh Endries <josh@endries.org>
 * @since 2.0.0
 */
class EmptyView {
	/**
	 * Render ourselves to the client.
	 */
	public function render() {
		// Do nothing.
	}
}
