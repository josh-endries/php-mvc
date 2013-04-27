<?php
require_once 'ControllerInterface.php';

class Controller implements ControllerInterface {
	protected $_action = null;
	protected $_actions = array();
	protected $_db = null;
	protected $_log = null;
	protected $_model = null;
	protected $_parameters = null;
	protected $_view = null;
	protected $_output = null;
	public function __construct(Log &$log, ADOConnection &$db, $action = null, $parameters = null) {
		$log->log(basename(__FILE__).'('.__LINE__.'): '.__METHOD__,PEAR_LOG_DEBUG);
		$this->_log = $log;
		$this->_db = $db;
		if (is_array($parameters) && count($parameters) > 0) {
			$this->_parameters = $parameters;
		}
		if (!is_null($action)) {
			if (array_key_exists($action,$this->_actions)) {
				if (!isset($this->_actions[$action]['view'])) {
					throw new Exception("No view found for action $action.");
				}
				if (!isset($this->_actions[$action]['function'])) {
					throw new Exception("No function found for action $action.");
				}
				if (isset($this->_actions['file'])) {
					require_once $this->_actions['file'];
				} else {
					require_once 'views/'.$this->_actions[$action]['view'].'.php';
				}
				$this->_view = new $this->_actions[$action]['view']($this->_log, $this->_db, $this->_parameters);
				$this->_action = $this->_actions[$action]['function'];
				$this->_log->log("Found action: $action.",PEAR_LOG_DEBUG);
			} else {
				throw new Exception("Could not find action '$action'.");
			}
		}
	}
	public function doAction() {
		$this->_log->log(basename(__FILE__).'('.__LINE__.'): '.__METHOD__,PEAR_LOG_DEBUG);
		try {
			if (!isset($this->_action) || is_null($this->_action)) {
				throw new Exception('No action is set.');
			}
			if (!isset($this->_model) || is_null($this->_model)) {
				throw new Exception('No model is set.');
			}
			if (isset($this->_parameters) && is_array($this->_parameters)) {
				$action = $this->_action;
				$this->_view->addOutput($this->_model->$action($this->_parameters));
			}
		} catch (Exception $e) {
			$this->_view->addException($e);
		}
		return $this->_view;
	}
}
?>