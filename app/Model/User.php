<?php
App::uses('AppModel', 'Model');

class User extends AppModel {

	public $validate = array(
		'username' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Trebuie sa specifici un nume pentru acest utilizator!',
				'last' => true,
			),
			'maxLength' => array(
				'rule' => array('maxLength', 50),
				'message' => 'Numele utilizatorului trebuie sa contina maxim 50 de caractere!',
				'last' => true,
			),
			'alphanumeric' => array(
				'rule' => array('alphanumeric'),
				'message' => 'Numele utilizatorului trebuie sa contina doar caractere alfanumerice!',
				'last' => true,
			),
			'isUnique' => array(
				'rule' => array('isUnique'),
				'message' => array('Ai mai adaugat acest utilizator pana acum!')
			)
		),
		'password' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Trebuie sa specifici o parola pentru acest utilizator!',
				'last' => true,
			),
			'maxLength' => array(
				'rule' => array('maxLength', 50),
				'message' => 'Parola trebuie sa contina maxim 50 de caractere!',
				'last' => true,
			),
		),
		'role' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Trebuie sa specifici un rol pentru acest utilizator!',
				'last' => true,
			),
			'inList' => array(
				'rule' => array('inList', array('admin', 'superuser')),
				'message' => 'Rol invalid!',
				'last' => true,
			),
		),
	);

	public $hasMany = array(
		'Bug' => array(
			'className' => 'Bug',
			'foreignKey' => 'user_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'BugMessage' => array(
			'className' => 'BugMessage',
			'foreignKey' => 'user_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
	);
	
	public function beforeSave() {
		if (isset($this->data[$this->alias]['password'])) {
			$this->data[$this->alias]['password'] = AuthComponent::password($this->data[$this->alias]['password']);
		}
		return true;
	}
}
