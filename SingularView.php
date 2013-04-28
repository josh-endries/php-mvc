<?php
require_once('View.php');

/**
 * Represents a view for which there exists only one instance during one page
 * load.
 * 
 * @author Josh Endries <josh@endries.org>
 * @since 2.0.0
 */
abstract class SingularView implements View {
	protected $parameters = Array();

	/**
	 * Create an instance of this view.
	 * 
	 * @param array $parameters Any necessary parameters for this view to be displayed.
	 * @throws PreexistingSingularViewException If this class is already instantiated.
	 */
	public function __construct(Array $parameters) {
		if (defined(__CLASS__.'Idempotency')) {
			throw new PreexistingSingularViewException("An instance of the ".__CLASS__." class already exists");
		} else {
			define(__CLASS__.'Idempotency', TRUE);
		}
		$this->parameters = $parameters;
	}
}