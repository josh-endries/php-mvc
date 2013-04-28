<?php
require_once('View.php');
require_once('PreexistingReusableViewException.php');

/**
 * The ReusableView class implements a view that can be used multiple times
 * within the same context (i.e., web page). Each use has its own unique ID,
 * and only one instance of each ID is allowed. For example: repeating an input
 * form that acquires an address multiple times, once for a billing contact
 * (ID 1), once for an administrative contact (ID 2) and once for a technical
 * contact (ID 3).
 * 
 * @author Josh Endries <josh@endries.org>
 * @since 2.0.0
 */
abstract class ReusableView implements View {
	protected $parameters = Array();
	
	/**
	 * Create a new ReusableView.
	 * 
	 * @param Array $parameters Any parameters necessary for the view to display its information.
	 * @param unknown_type $id The unique identifier for this instance.
	 * @throws PreexistingReusableViewException if there is already an instance of the identified view.
	 */
	public function __construct(Array $parameters, $id) {
		if (defined(__CLASS__.$id.'Idempotency')) {
			throw new PreexistingReusableViewException("An instance of the ".__CLASS__." class with ID ".$id." already exists");
		} else {
			define(__CLASS__.$id.'Idempotency', TRUE);
		}
		$this->parameters = $parameters;
		$this->id = $id;
	}
}