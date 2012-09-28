<?php
App::uses('AppModel', 'Model');

class Suggestion extends AppModel {

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
		'station_group_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'ID invalid!',
			),
		),
		'id_ratt' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'ID invalid!',
			),
		),
		'name' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Trebuie sa alegi un nume!',
				'last' => true
			),
			'between' => array(
				'rule' => array('between', 1, 100),
				'message' => 'Numele trebuie sa contina intre 1 si 100 de caractere!',
				'last' => true
			),
			'alphaNumericSpaceParanthesisSlash' => array(
				'rule' => array('alphaNumericSpaceParanthesisSlash'),
				'message' => 'Numele trebuie sa contina doar caractere alfanumerice, spatiu, slash sau paranteze!',
			),
		),
		'direction' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Trebuie sa alegi o directie pentru aceasta statie!',
				'last' => true
			),
			'between' => array(
				'rule' => array('between', 1, 50),
				'message' => 'Directia statiei trebuie sa contina intre 1 si 50 de caractere!',
				'last' => true
			),
			'alphaNumericSpaceParanthesisSlash' => array(
				'rule' => array('alphaNumericSpaceParanthesisSlash'),
				'message' => 'Directia statiei trebuie sa contina doar caractere alfanumerice, punct, spatiu, slash sau paranteze!',
			),
		),
		'lat' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Latitudine invalida!',
			),
		),
		'lng' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Longitudine invalida!',
			),
		),
		'region' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Trebuie sa alegi o regiune pentru aceasta statie!',
				'last' => true
			),
			'inlist' => array(
				'rule' => array('inlist', array('N', 'NV', 'NE', 'V', 'E', 'S', 'SV', 'SE', 'C', 'CN', 'CV', 'CS', 'CE')),
				'message' => 'Regiunea este invalida!',
			),
		),
		'node' => array(
			'boolean' => array(
				'rule' => array('boolean'),
				'message' => 'Valoare invalida!',
			),
		),
		'type' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Trebuie sa specifici un tip!',
				'last' => true
			),
			'inList' => array(
				'rule' => array('inList', array('tv', 'tb', 'ab', 'am', 'ae')),
				'message' => 'Tipul este invalid!',
			),
		),
	);

	public $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
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
		'Line' => array(
			'className' => 'Line',
			'foreignKey' => 'line_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'StationGroup' => array(
			'className' => 'StationGroup',
			'foreignKey' => 'station_group_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
	);
	
	public $findMethods = array('formatted' => true);
	
	public $suggestion;
	
	protected $_suggestionFields = array(
		'Station' => array('id_ratt', 'name', 'direction', 'lat', 'lng', 'region', 'node'),
		'Line' => array('id_ratt', 'name', 'type'),
		'StationGroup' => array('name')
	);
	
	protected function _findFormatted($state, $query, $results = array()){
		if($state == 'before'){
			return $query;	
		}
		
		$suggestions = array();
		foreach($results as $result){
			$suggestion = array();
			$suggestion['Suggestion'] = array(
				'id' => $result['Suggestion']['id'],
				'user_id' => $result['Suggestion']['user_id'],
				'created' => $result['Suggestion']['created'],
				'suggestions' => array()
			);
			$suggestion['User'] = array(
				'id' => $result['User']['id'],
				'username' => $result['User']['username']
			);
			
			if($result['Suggestion']['station_id'] != 0){
				$suggestion['Suggestion']['model'] = 'Station';
				$suggestion['Suggestion']['model_id'] = $result['Suggestion']['station_id'];
			} elseif($result['Suggestion']['line_id'] != 0){
				$suggestion['Suggestion']['model'] = 'Line';
				$suggestion['Suggestion']['model_id'] = $result['Suggestion']['line_id'];
			} elseif($result['Suggestion']['station_group_id'] != 0){
				$suggestion['Suggestion']['model'] = 'StationGroup';
				$suggestion['Suggestion']['model_id'] = $result['Suggestion']['station_group_id'];
			}
			
			foreach($this->_suggestionFields[$suggestion['Suggestion']['model']] as $field){
				if($result['Suggestion'][$field] != $result[$suggestion['Suggestion']['model']][$field]){
					$suggestion['Suggestion']['suggestions'][$field] = array(
						'actual' => $result[$suggestion['Suggestion']['model']][$field],
						'suggested' => $result['Suggestion'][$field]
					);
				}
			}
			
			$suggestions[] = $suggestion;
		}
				
		return $results = $suggestions;
	}
	
	public function accept(){
		$this->suggestion = $this->read();
		
		$save = array();
		
		if($this->suggestion['Suggestion']['station_id'] != 0){
			$model = 'Station';
			$save['id'] = $this->suggestion['Suggestion']['station_id'];
		} elseif($this->suggestion['Suggestion']['line_id'] != 0){
			$model = 'Line';
			$save['id'] = $this->suggestion['Suggestion']['line_id'];
		} elseif($this->suggestion['Suggestion']['station_group_id'] != 0){
			$model = 'StationGroup';
			$save['id'] = $this->suggestion['Suggestion']['station_group_id'];
		}
		
		foreach($this->_suggestionFields[$model] as $field){
			$save[$field] = $this->suggestion['Suggestion'][$field];
		}
		
		return $this->$model->save($save);
	}
}
