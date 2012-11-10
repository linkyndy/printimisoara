<?php

App::uses('AppModel', 'Model');

class ComputedTime extends AppModel {

	public $displayField = 'time';

	public $validate = array(
		'station_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'ID invalid!',
			),
		),
		'line_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'ID invalid!',
			),
		),
		'time' => array(
			'time' => array(
				'rule' => array('time'),
				'message' => 'Ora invalida!',
			),
		),
		'log' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Trebuie sa existe un log pentru timp!',
				'last' => true,
			),
		),
	);

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Station' => array(
			'className' => 'Station',
			'foreignKey' => 'station_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Line' => array(
			'className' => 'Line',
			'foreignKey' => 'line_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
