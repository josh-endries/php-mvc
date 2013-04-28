<?php
class RedirectException extends Exception {
	private $url;
	
	public function __construct($url, $message = NULL) {
		$this->url = $url;
		parent::__construct($message);
	}
	
	public function getURL() {
		return $this->url;
	}
} 
?>