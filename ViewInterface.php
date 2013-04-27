<?php
interface ViewInterface {
	public function __construct(Log &$log, ADOConnection &$db);
	public function addFile($filename, $position = null);
	public function addException(Exception &$e);
	public function display();
}
?>