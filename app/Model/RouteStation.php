<?php
App::uses('AppModel', 'Model');
/**
 * Station Model
 *
 * @property StationGroup $StationGroup
 * @property StationLine $StationLine
 * @property Time $Time
 */
class RouteStation extends AppModel {

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'route_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Invalid route ID!',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'station_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Invalid station ID!',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Route' => array(
			'className' => 'Route',
			'foreignKey' => 'route_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Station' => array(
			'className' => 'Station',
			'foreignKey' => 'station_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
	);
}
