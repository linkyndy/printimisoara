<?php
App::uses('AppModel', 'Model');

class Config extends AppModel {
	
	public $useTable = 'config';

	public $validate = array(
		'vacation_start' => array(
			'date' => array(
				'rule' => array('date', 'ymd'),
				'message' => 'Invalid date',
			),
		),
		'vacation_end' => array(
			'date' => array(
				'rule' => array('date', 'ymd'),
				'message' => 'Invalid date',
			),
		),
		'near_stations_radius' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Invalid radius',
			),
		),
		'day_change' => array(
			'time' => array(
				'rule' => array('time'),
				'message' => 'Invalid day change time',
			),
		),
		'max_distance_in_minutes' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Invalid maximum distance in minutes between stations',
			),
		),
	);
	
	public function getConfig(){
		$config = $this->find();
		Configure::write('Config', $config['Config']);
		
		$this->_setIsVacation();
		$this->_setIsCloseToVacation();
	}
	
	private function _setIsVacation(){
		if(
			time() >= strtotime(Configure::read('Config.vacation_start')) && 
			time() <= strtotime(Configure::read('Config.vacation_end'))
		){
			Configure::write('Config.is_vacation', true);
		} else {
			Configure::write('Config.is_vacation', false);
		}
	}
	
	private function _setIsCloseToVacation(){
		if(
			strtotime(Configure::read('Config.vacation_start')) > time() &&
			date('j', strtotime(Configure::read('Config.vacation_start')) - time()) <= 7
		){
			Configure::write('Config.is_close_to_vacation', true);
		} else {
			Configure::write('Config.is_close_to_vacation', false);
		}
	}
}