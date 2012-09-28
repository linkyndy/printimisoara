<?php
App::uses('Shell', 'Console');
App::uses('Config', 'Model');

class AppShell extends Shell {
	public $uses = array('Config');
	
	public function beforeFilter(){
		parent::beforeFilter();
		//$this->Config = new Config;
		$this->Config->getConfig();
	}
}

?>