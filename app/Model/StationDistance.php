<?php
App::uses('AppModel', 'Model');

class StationDistance extends AppModel {

	public $validate = array(
		'from_station_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Invalid station ID',
			),
		),
		'to_station_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Invalid station ID',
			),
		),
		'minutes' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Distanta invalida!',
			),
		),
		'time' => array(
			'time' => array(
				'rule' => array('time'),
				'message' => 'Timp invalid!'
			)
		),
		'day' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Trebuie sa alegi un tip de zi!',
				'last' => true,
			),
			'inlist' => array(
				'rule' => array('inlist', array('L', 'LV', 'S', 'D')),
				'message' => 'Tip de zi invalid!',
			),
		),
		// Just a helper to display a more prettier form
		'from' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Trebuie sa alegi o statie de plecare!',
			),
		),
		'to' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Trebuie sa alegi o statie de sosire!',
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