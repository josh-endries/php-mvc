<?php
/**
 * The View class determines what data is displayed to the client and how that
 * data is displayed.
 * 
 * @author Josh Endries <josh@endries.org>
 * @since 2.0.0
 */
interface View {
	/**
	 * Display the view to the client.
	 */
	public function render();
}