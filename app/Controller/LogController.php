<?php
App::uses('AppController', 'Controller');

class LogController extends AppController {
	
	public $paginate = array(
		'order' => array(
			'Log.created' => 'DESC'
		),
		'limit' => 50
	);

	public function admin_index($type = null) {
		$this->Log->recursive = 0;
		if($type){
			$this->set('log', $this->paginate('Log', array('Log.type' => $type)));
		} else{
			$this->set('log', $this->paginate());
		}
	}
}
