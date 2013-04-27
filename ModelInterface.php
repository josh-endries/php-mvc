<?php
interface ModelInterface {
	public function __construct(Log &$log, ADOConnection &$db);
}
?>