<?php
App::uses('AppModel', 'Model');

class Bug extends AppModel {

	public $validate = array(
		'user_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Utilizator invalid!',
			),
		),
		'type' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Trebuie sa specifici o categorie pentru bug!',
				'last' => true,
			),
			'inList' => array(
				'rule' => array('inList', array('routes', 'times', 'database', 'location', 'app')),
				'message' => 'Categorie invalida!',
			),
		),
		'title' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Trebuie sa specifici un titlu pentru bug!',
				'last' => true,
			),
			'maxLength' => array(
				'rule' => array('maxLength', 100),
				'message' => 'Titlul trebuie sa contina maxim 100 de caractere!',
			),
		),
		'bug' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Trebuie sa scrii un mesaj despre bug-ul intalnit!',
				'last' => true,
			),
			'maxLength' => array(
				'rule' => array('maxLength', 1000),
				'message' => 'Mesajul trebuie sa contina maxim 1000 de caractere!',
			),
		),
		'status' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Status invalid',
				'last' => true,
			),
			'inList' => array(
				'rule' => array('inList', array('new', 'pending', 'invalid', 'resolved', 'future')),
				'message' => 'Status invalid',
			),
		),
	);
	
	public $hasMany = array(
		'BugMessage' => array(
			'className' => 'BugMessage',
			'foreignKey' => 'bug_id',
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
	
	public $belongsTo = array(
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