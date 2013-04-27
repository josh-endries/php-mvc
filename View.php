<?php
require_once 'ViewInterface.php';

class View implements ViewInterface {
	private $_files = array();
	private $_exception = null;
	private $_output = array();
	private $_parameters = array();
	public function __construct(Log &$log, ADOConnection &$db, $parameters = null) {
		$log->log(basename(__FILE__).'('.__LINE__.'): '.__METHOD__,PEAR_LOG_DEBUG);
		$this->_log = $log;
		$this->_db = $db;
		if (!is_null($parameters) && !is_array($parameters)) {
			throw new Exception('Parameters are not an array.');
		}
		$this->_parameters = $parameters;
	}
	public function addOutput($input) {
		$this->_output[] = $input;
	}
	public function addFile($filename, $position = null) {
		$this->_log->log(basename(__FILE__).'('.__LINE__.'): '.__METHOD__,PEAR_LOG_DEBUG);
		if (!is_string($filename)) {
			$reason = 'File name parameter is not a string.';
			$this->_log->log($reason,PEAR_LOG_ERR);
			throw new Exception($reason);
		}
		if (is_null($position)) {
			$position = count($this->_files);
		} else {
			if (!is_int($position)) {
				$reason = "Position $position is not an integer.";
				$this->_log->log($reason,PEAR_LOG_ERR);
				throw new Exception($reason);
			}
			if ($position < 0) {
				$reason = "Position $position is less than zero.";
				$this->_log->log($reason,PEAR_LOG_ERR);
				throw new Exception($reason);
			}
			if ($position > count($this->_files)) {
				$this->_log->log("Position is larger than the number of files.",PEAR_LOG_NOTICE);
				$position = count($this->_files);
			}
		}
		if (!file_exists($filename)) {
			$reason = "File $filename does not exist.";
			$this->_log->log($reason,PEAR_LOG_ERR);
			throw new Exception($reason);
		}
		if (!is_readable($filename)) {
			$reason = "File $filename is not readable.";
			$this->_log->log($reason,PEAR_LOG_ERR);
			throw new Exception($reason);
		}
		$found = false;
		foreach ($this->_files as $file) {
			if ($file === $filename) {
				$found = true;
				$this->_log->log("File $filename is already included.",PEAR_LOG_NOTICE);
				break;
			}
		}
		if (!$found) {
			$this->_files = array_merge(array_slice($this->_files, 0, $position), array($filename), array_slice($this->_files, $position));
			$this->_log->log("File $filename added.",PEAR_LOG_DEBUG);
		}
	}
	public function addException(Exception &$e) {
		$this->_exception = $e;
		$this->addFile(ROOT.'views/includes/exception.php',1);
	}
	public function display() {
		foreach ($this->_files as $file) {
			include($file);
		}
	}
}
?>