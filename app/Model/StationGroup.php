<?php
App::uses('AppModel', 'Model');

class StationGroup extends AppModel {

	public $displayField = 'name';

	public $validate = array(
		'name' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Trebuie sa alegi un nume pentru acest grup de statii!',
				'last' => true
			),
			'between' => array(
				'rule' => array('between', 1, 100),
				'message' => 'Numele grupului de statii trebuie sa contina intre 1 si 100 de caractere!',
				'last' => true
			),
			'alphaNumericSpaceParanthesisSlash' => array(
				'rule' => array('alphaNumericSpaceParanthesisSlash'),
				'message' => 'Numele grupului de statii trebuie sa contina doar caractere alfanumerice, punct, spatiu, slash sau paranteze!',
				'last' => true
			),
			'isUnique' => array(
				'rule' => array('isUnique'),
				'message' => array('Ai mai adaugat aceast grup de statii pana acum!')
			)
		),
	);

	public $hasMany = array(
		'Station' => array(
			'className' => 'Station',
			'foreignKey' => 'station_group_id',
			'dependent' => true,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);

}
