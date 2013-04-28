<?php
require_once(dirname(__FILE__).DIRECTORY_SEPARATOR."Message_Priority.php");

class Message {
	private $priority;
	private $content;

	public function __construct($priority, $content) {
		$this->priority = $priority;
		$this->content = $content;
	}

	public function getContent() {
		return $this->content;
	}

	public function getPriority() {
		return $this->priority;
	}

	public function getPriorityName() {
		return Message_Priority::toName($this->priority);
	}
}
