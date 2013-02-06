<?php
App::uses('AppModel', 'Model');

class Coverage extends AppModel {
	public $useTable = 'coverage';
	
	public $validate = array(
		'line_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'ID invalid!',
			),
		),
		'coverage' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Acoperire invalida!',
			),
		),
		'coverage_L' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Acoperire invalida!',
			),
		),
		'coverage_LV' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Acoperire invalida!',
			),
		),
		'coverage_S' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Acoperire invalida!',
			),
		),
		'coverage_D' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Acoperire invalida!',
			),
		),
	);
	
	public $belongsTo = array(
		'Line' => array(
			'className' => 'Line',
			'foreignKey' => 'line_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
	);
}
