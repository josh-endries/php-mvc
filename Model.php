<?php
require_once 'ModelInterface.php';

class Model implements ModelInterface {
	var $_db;
	var $_log;
	function __construct(&$log, &$db) {
		$this->_log = $log;
		$this->_log->log(basename(__FILE__).'('.__LINE__.'): '.__CLASS__.'::'.__FUNCTION__,PEAR_LOG_DEBUG);
		$this->_db = $db;
	}
}
?>