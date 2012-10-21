<?php
App::uses('AppModel', 'Model');

class Maintenance extends AppModel {
	
	public $useTable = 'maintenance';

	public $validate = array(
		'uncovered_lines' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				'message' => 'Invalid field',
			),
		),
	);
	
	public function get($key = null){
		$maintenance = $this->find();
		return (!is_null($key)) ? $maintenance['Maintenance'][$key] : $maintenance['Maintenance'];
	}
	
	public function set($key, $value){
		return $this->save(array('Maintenance' => array('id' => 1, $key => $value)));
	}
	
	public function getMaintenance(){
		$maintenance = $this->find();
		
		$maintenance['Maintenance']['uncovered_lines'] = 
			(!empty($maintenance['Maintenance']['uncovered_lines'])) ? 
				explode(',', $maintenance['Maintenance']['uncovered_lines']) : 
				array();
		
		Configure::write('Maintenance', $maintenance['Maintenance']);
	}
}