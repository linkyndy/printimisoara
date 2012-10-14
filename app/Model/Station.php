<?php
App::uses('AppModel', 'Model');

class Station extends AppModel {

	public $displayField = 'name';

	public $validate = array(
		'id_ratt' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'ID invalid!',
			),
		),
		/*'station_group_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'ID invalid!',
			),
		),*/
		'name' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Trebuie sa alegi un nume pentru aceasta statie!',
				'last' => true,
			),
			'between' => array(
				'rule' => array('between', 1, 100),
				'message' => 'Numele statiei trebuie sa contina intre 1 si 100 de caractere!',
				'last' => true,
			),
			'alphaNumericSpaceParanthesisSlash' => array(
				'rule' => array('alphaNumericSpaceParanthesisSlash'),
				'message' => 'Numele statiei trebuie sa contina doar caractere alfanumerice, spatiu, slash sau paranteze!',
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
				'last' => true,
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
				'last' => true,
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
	);
	
	public $virtualFields = array(
		'name_direction' => 'CONCAT(Station.name, " (", Station.direction, ")")'
	);

	public $belongsTo = array(
		'StationGroup' => array(
			'className' => 'StationGroup',
			'foreignKey' => 'station_group_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
	);

	public $hasMany = array(
		'StationLine' => array(
			'className' => 'StationLine',
			'foreignKey' => 'station_id',
			'dependent' => true,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'Time' => array(
			'className' => 'Time',
			'foreignKey' => 'station_id',
			'dependent' => true,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'FromStationConnection' => array(
			'className' => 'StationConnection',
			'foreignKey' => 'from_station_id',
			'dependent' => true,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'ToStationConnection' => array(
			'className' => 'StationConnection',
			'foreignKey' => 'to_station_id',
			'dependent' => true,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'FromStationPoint' => array(
			'className' => 'StationPoint',
			'foreignKey' => 'from_station_id',
			'dependent' => true,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'ToStationPoint' => array(
			'className' => 'StationPoint',
			'foreignKey' => 'to_station_id',
			'dependent' => true,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'FromStationDistance' => array(
			'className' => 'StationDistance',
			'foreignKey' => 'from_station_id',
			'dependent' => true,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'ToStationDistance' => array(
			'className' => 'StationDistance',
			'foreignKey' => 'to_station_id',
			'dependent' => true,
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
	
	public $coords;
	public $radius;
	public $stationLine = array();
	
	/**
	 * Get all stations within the default radius
	 * of the given coordinates
	 *
	 * @param $coords
	 *   Array of coordinates
	 *
	 * @return mixed
	 *   Array of found stations, false otherwise
	 */
	public function nearStations($coords = array()){
		if (!$this->_validCoords($coords)){
			$this->_logInvalidCoords();
			return false;	
		}
		
		$this->radius = Configure::read('Config.near_stations_radius');
		
		$this->recursive = 0;
		$this->virtualFields['distance'] = '
			ROUND(
				ACOS(
					SIN(RADIANS('.$this->coords['lat'].')) * 
					SIN(RADIANS(lat)) + 
					COS(RADIANS('.$this->coords['lat'].')) * 
					COS(RADIANS(lat)) * 
					COS(RADIANS(lng) - RADIANS('.$this->coords['lng'].'))
				) * 
				6371000
			)
		';
		
		do {
			$stationGroupId = $this->field('station_group_id', array(
				'Station.distance <=' => $this->radius,
			), 'Station.distance ASC');	
		} while(
			$stationGroupId === false && 
			$this->radius <= 1000 && 
			$this->radius += 100
		);
		
		$results = $this->find('list', array(
			'fields' => array('Station.id'),
			'conditions' => array('Station.station_group_id' => $stationGroupId),
			'order' => 'Station.distance ASC'
		));
		
		unset($this->virtualFields['distance']);

		return !empty($results) ? $results : false;
	}
	
	/*public function followingStations($stationLineId = null){
		if(!$this->stationLine = $this->StationLine->find('first', array('conditions' => array('StationLine.id' => $stationLineId)))){
			return false;
		}

		$line_stations = $this->StationLine->find('all', array(
			'conditions' => array('StationLine.line_id' => $this->stationLine['Line']['id']),
			'order' => 'StationLine.order ASC',
		));
		
		if($this->stationLine['StationLine']['end']){
			return array();	
		}
		
		$following = array();
		foreach($line_stations as $key => &$station){
			if($station['StationLine']['id'] == $this->stationLine['StationLine']['id']){
				unset($line_stations[$key]);
				break;
			} else {
				array_push($line_stations, array_shift($line_stations));	
			}
		}
		foreach($line_stations as $station){
			$following[] = $station['StationLine']['station_id'];
			if($station['StationLine']['end']){
				break;	
			}
		}
		
		return $following;
	}*/
	
	public function validateNodes($stationGroupIds = array()){
		if(!is_array($stationGroupIds)){
			$this->_logInvalidStarionGroupArray();
			return false;	
		}
		
		foreach($stationGroupIds as $stationGroupId){
			$this->StationGroup->id = $stationGroupId;
			if(!$this->StationGroup->exists()){
				throw new NotFoundException(__('Grup de statii invalid'));
			}
			
			$stations = $this->find('list', array(
				'conditions' => array('Station.station_group_id' => $stationGroupId),
				'fields' => array('Station.id')
			));
			
			foreach($stations as $stationId){
				$this->id = $stationId;
				if(!$this->saveField('node', ($this->_validNode($this->id)) ? 1 : 0)){
					return false;	
				}
			}
		}
		
		return true;
	}
	
	protected function _validNode($station_id){
		$this->id = $station_id;
		if(!$this->exists()){
			throw new NotFoundException(__('Statie invalida'));
		}
		
		$station_group_id = $this->field('station_group_id');
		
		if(
			$this->find('count', array('conditions' => array(
				'Station.station_group_id' => $station_group_id
			))) > 2
		){
			return true;	
		}
		
		$lines = $this->StationLine->find('list', array(
			'conditions' => array('StationLine.station_id' => $station_id),
			'fields' => array('StationLine.line_id')
		));
		
		foreach($lines as $line_id){
			$order = $this->StationLine->field('order', array(
				'StationLine.station_id' => $station_id,
				'StationLine.line_id' => $line_id
			));
			
			if(
				!$next_station_id = $this->StationLine->field('station_id', array(
					'StationLine.line_id' => $line_id, 
					'StationLine.order' => ++$order
				))
			){
				$next_station_id = $this->StationLine->field('station_id', array(
					'StationLine.line_id' => $line_id, 
					'StationLine.order' => 1
				));
			}
			
			$next_station_group_id = $this->field('station_group_id', array('Station.id' => $next_station_id));
			$next_stations = $this->find('list', array(
				'conditions' => array('Station.station_group_id' => $next_station_group_id),
				'fields' => array('Station.id')
			));
			
			$next_lines = $this->StationLine->find('list', array(
				'conditions' => array('StationLine.station_id' => $next_stations),
				'fields' => array('StationLine.line_id')
			));
			
			foreach($lines as $line){
				if(!in_array($line, $next_lines)){
					return true;	
				}
			}
		}
		
		$station_group_id = $this->field('station_group_id', array('Station.id' => $station_id));
		$cross_stations = $this->find('list', array(
			'conditions' => array('Station.station_group_id' => $station_group_id, 'NOT' => array('Station.id' => $station_id)),
			'fields' => array('Station.id')
		));
		
		foreach($cross_stations as $cross_station_id){
			$cross_lines = $this->StationLine->find('list', array(
				'conditions' => array('StationLine.station_id' => $cross_station_id),
				'fields' => array('StationLine.line_id')
			));
			
			foreach($cross_lines as $cross_line_id){
				$cross_order = $this->StationLine->field('order', array(
					'StationLine.station_id' => $cross_station_id,
					'StationLine.line_id' => $cross_line_id
				));
				
				if(
					!$next_cross_station_id = $this->StationLine->field('station_id', array(
						'StationLine.line_id' => $cross_line_id, 
						'StationLine.order' => ++$cross_order
					))
				){
					$next_cross_station_id = $this->StationLine->field('station_id', array(
						'StationLine.line_id' => $cross_line_id, 
						'StationLine.order' => 1
					));
				}
				
				$next_cross_station_group_id = $this->field('station_group_id', array('Station.id' => $next_cross_station_id));
				$next_cross_stations = $this->find('list', array(
					'conditions' => array('Station.station_group_id' => $next_cross_station_group_id),
					'fields' => array('Station.id')
				));
				
				$next_cross_lines = $this->StationLine->find('list', array(
					'conditions' => array('StationLine.station_id' => $next_cross_stations),
					'fields' => array('StationLine.line_id')
				));
				
				foreach($cross_lines as $cross_line){
					if(!in_array($cross_line, $next_cross_lines)){
						return true;	
					}
				}
			}
		}
		return false;
	}
	
	protected function _validCoords($coords){
		if(
			isset($coords['lat']) &&
			isset($coords['lng']) &&
			is_numeric($coords['lat']) &&
			is_numeric($coords['lng'])
		){
			$this->coords = array(
				'lat' => $coords['lat'],
				'lng' => $coords['lng']
			);
			return true;
		}
		return false;
	}
	
	protected function _logInvalidCoords(){
		$type = 'hijack';
		$message = 'Coordonatele primite pentru gasirea statiilor apropiate sunt invalide';
		$this->_logWrite($type, $message);
	}
	
	protected function _logInvalidStationGroupArray(){
		$type = 'warning';
		$message = 'Functia care valideaza nodurile nu a fost apelata cu un array de grupuri de statii';
		$this->_logWrite($type, $message);
	}
	
	protected function _logWrite($type, $message){
		CakeLog::write($type, $message);
	}
}
