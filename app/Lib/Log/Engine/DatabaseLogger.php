<?php

App::uses('CakeLogInterface', 'Log');
App::uses('Log', 'Model');

class DatabaseLogger implements CakeLogInterface {
	public $Log;
	
	public function __construct($options = array()){
		$this->Log = new Log;
	}
	
	public function write($type, $message){
		$this->Log->create();
		return $this->Log->save(array(
			'type' => $type,
			'message' => $message
		));
	}
}

?>