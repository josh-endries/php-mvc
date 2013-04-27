<?php
interface ControllerInterface {
	public function __construct(Log &$log, ADOConnection &$db, $action = null, $parameters = null);
	public function doAction();
}
?>