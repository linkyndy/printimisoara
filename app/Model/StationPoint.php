<?php
App::uses('AppModel', 'Model');

class StationPoint extends AppModel {

	public $validate = array(
		'from_station_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Invalid station ID',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => true, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'to_station_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Invalid station ID',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => true, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'points' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Empty points',
				//'allowEmpty' => false,
				//'required' => false,
				'last' => true, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
			'maxLength' => array(
				'rule' => array('maxLength', 1000),
				'message' => 'Points is too long',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => true, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);
	
	public $belongsTo = array(
		'FromStation' => array(
			'className' => 'Station',
			'foreignKey' => 'from_station_id',
			'conditions' => '',
			'fields' => array('FromStation.id', 'FromStation.id_ratt', 'FromStation.station_group_id', 'FromStation.name', 'FromStation.direction', 'FromStation.lat', 'FromStation.lng', 'FromStation.region'),
			'order' => ''
		),
		'ToStation' => array(
			'className' => 'Station',
			'foreignKey' => 'to_station_id',
			'conditions' => '',
			'fields' => array('ToStation.id', 'ToStation.id_ratt', 'ToStation.station_group_id', 'ToStation.name', 'ToStation.direction', 'ToStation.lat', 'ToStation.lng', 'ToStation.region'),
			'order' => ''
		)
	);
}