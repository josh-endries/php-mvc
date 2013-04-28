<?php
class Message_Priority {
	/*
	 * We start at 0 here to match Exception->code, which defaults to 0.
	 */
	const ERROR = 0, WARNING = 1, INFO = 2;

	public static function toName(Message_Priority $i) {
		if ($i == Message_Priority::INFO) {
			return 'info';
		} else if ($i == Message_Priority::WARNING) {
			return 'warning';
		} else if ($i == Message_Priority::ERROR) {
			return 'error';
		} else {
			return NULL;
		}
	}
}