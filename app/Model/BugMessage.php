<?php
App::uses('AppModel', 'Model');

class BugMessage extends AppModel {

	public $validate = array(
		'bug_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Bug invalid!',
			),
		),
		'user_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Utilizator invalid!',
			),
		),
		'message' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Trebuie sa scrii un mesaj despre bug!',
				'last' => true,
			),
			'maxLength' => array(
				'rule' => array('maxLength', 1000),
				'message' => 'Mesajul trebuie sa contina maxim 1000 de caractere!',
			),
		),
	);
	
	public $belongsTo = array(
		'Bug' => array(
			'className' => 'Bug',
			'foreignKey' => 'bug_id',
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'type' => ''
		),
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'type' => ''
		),
	);
}