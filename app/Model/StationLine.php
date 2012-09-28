<?php
App::uses('AppModel', 'Model');
/**
 * StationLine Model
 *
 * @property Station $Station
 * @property Line $Line
 */
class StationLine extends AppModel {
/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'station_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'ID invalid!',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'line_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'ID invalid!',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'order' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Ordine invalida!',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'end' => array(
			'boolean' => array(
				'rule' => array('boolean'),
				'message' => 'Valoare invalida!',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'false_start' => array(
			'boolean' => array(
				'rule' => array('boolean'),
				'message' => 'Valoare invalida!',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'false_end' => array(
			'boolean' => array(
				'rule' => array('boolean'),
				'message' => 'Valoare invalida!',
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
 
 	public $hasMany = array(
		'FollowingStationLine' => array(
			'className' => 'FollowingStationLine',
			'foreignKey' => 'reference_station_line_id',
			'dependent' => false,//rebuild cache instead
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
	
	public $belongsTo = array(
		'Station' => array(
			'className' => 'Station',
			'foreignKey' => 'station_id',
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'type' => 'inner'
		),
		'Line' => array(
			'className' => 'Line',
			'foreignKey' => 'line_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
	
	public $coords;
	public $radius;
	public $stationLine;
	public $lineEnds = null;//number of ends each line has; available only when route data is built
	
	//private $__followingStationLines = array();//station lines to follow for [stationLineId] - now Cached
	
	/*public function nearStationLines($coords = array()){
		if(!$this->_validCoords($coords)){
			$this->_logInvalidCoords();
			return false;	
		}
		
		$this->radius = Configure::read('Config.near_stations_radius');
		
		$this->Station->recursive = 0;
		$this->Station->virtualFields['distance'] = '
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

		do{
			$results = $this->Stationfind('list', array(
				'fields' => array('StationLine.id'),
				
				'contain' => array(
					'Station' => array(
						'fields' => array('Station.distance'),
						'conditions' => array('Station.distance <=' => $this->radius),
						'order' => 'Station.distance ASC',
					)
				)
				
			));	
		}while(
			count($results) == 0 && 
			$this->radius <= 1000 && 
			$this->radius += 100
		);
		
		unset($this->virtualFields['distance']);

		return !empty($results) ? $results : false;
	}*/
	/*public $read = 0;
	public $sql = 0;
	public $write = 0;*/
	public function followingStationLines($stationLineId = null/*, $stopStationLineIds = array()*/){
		/*if(!$this->stationLine = $this->find('first', array('conditions' => array('StationLine.id' => $stationLineId)))){
			return false;
		}*/
		
		/*if(isset($this->__followingStationLines[$stationLineId])){
			return $this->__followingStationLines[$stationLineId];	
		}*/
		//$read = microtime(true);
		if(($followingStationLines = Cache::read('FollowingStationLines.'.$stationLineId)) !== false){
			//$this->read = $this->read + (microtime(true) - $read);
			return $followingStationLines;	
		}
		//$this->read = $this->read + (microtime(true) - $read);
		
		//$sql = microtime(true);
		$following = $this->FollowingStationLine->find('all', array(
			'conditions' => array('FollowingStationLine.reference_station_line_id' => $stationLineId),
			'order' => 'FollowingStationLine.order ASC'
		));
		//$this->sql = $this->sql + (microtime(true) - $sql);
		/*if($this->stationLine['StationLine']['end']){
			return array();	
		}
		
		if(isset($this->__followingStationLines[$this->stationLine['StationLine']['id']])){
			return $this->__followingStationLines[$this->stationLine['StationLine']['id']];
		}
		
		$stationLines = $this->find('all', array(
			'conditions' => array('StationLine.line_id' => $this->stationLine['Line']['id']),
			'order' => 'StationLine.order ASC'
		));*/
		
		/*$stationLinesFirst = $this->find('all', array(
			'conditions' => array(
				'StationLine.line_id' => $this->stationLine['Line']['id'],
				'StationLine.order >' => $this->stationLine['StationLine']['order']
			),
			'order' => 'StationLine.order ASC'
		));
		$stationLinesLast = $this->find('all', array(
			'conditions' => array(
				'StationLine.line_id' => $this->stationLine['Line']['id'],
				'StationLine.order <' => $this->stationLine['StationLine']['order']
			),
			'order' => 'StationLine.order ASC'
		));
		$stationLines = array_merge($stationLinesFirst, $stationLinesLast);*/
		
		/*$following = array();
		foreach($stationLines as $key => &$stationLine){
			if($stationLine['StationLine']['id'] == $this->stationLine['StationLine']['id']){
				unset($stationLines[$key]);
				break;
			} else {
				array_push($stationLines, array_shift($stationLines));	
			}
		}
		foreach($stationLines as $stationLine){
			$following[] = $stationLine;
			if(
				$stationLine['StationLine']['end'] || 
				in_array($stationLine['StationLine']['id'], $stopStationLineIds)
			){
				break;	
			}
		}*/
		
		/*for($i = 0; $i < count($stationLines); $i++){
			$following = array();
			foreach($stationLines as &$stationLine){
				if(
					$stationLine['StationLine']['end'] || 
					in_array($stationLine['StationLine']['id'], $stopStationLineIds)
				){
					break;
				}
				$following[] = $stationLine;
			}
			$stationLine2 = array_shift($following);
			$this->__followingStationLines[$stationLine2['StationLine']['id']] = $following;
			array_push($stationLines, array_shift($stationLines));	
		}
		
		return $this->__followingStationLines[$this->stationLine['StationLine']['id']];*/
		
		//$this->__followingStationLines[$stationLineId] = $following;
		//$write = microtime(true);
		Cache::write('FollowingStationLines.'.$stationLineId, $following);
		//$this->write = $this->write + (microtime(true) - $write);
		return $following;
	}
	
	public function computeFalseStartEnd(){
		$this->virtualFields['ends'] = 'SUM(StationLine.end)';
		$this->lineEnds = $this->find('list', array(
			'fields' => array('StationLine.line_id', 'StationLine.ends'),
			'group' => 'StationLine.line_id'
		));
		unset($this->virtualFields['ends']);
		
		$this->updateAll(
			array(
				'StationLine.start' => 0, 
				'StationLine.false_start' => 0, 
				'StationLine.false_end' => 0
			), 
			array(
				'StationLine.id >' => 0
			)
		);
		
		foreach($this->lineEnds as $lineId => $lineEnd){
			$stationLines = $this->find('all', array(
				'conditions' => array('StationLine.line_id' => $lineId),
				'order' => 'StationLine.order ASC',
				'contain' => false
			));
				
			if($lineEnd == 2){
				foreach($stationLines as $i => $stationLine){
					if($stationLine['StationLine']['end']){
						if(isset($stationLines[$i + 1])){
							$this->id = $stationLines[$i + 1]['StationLine']['id'];
							$this->saveField('start', 1);
						} else {
							$this->id = $stationLines[0]['StationLine']['id'];
							$this->saveField('start', 1);
						}
					}
				}
			} elseif($lineEnd == 1){		
				$inLoop = false;
				foreach($stationLines as $i => $stationLine){
					$stationGroupId = $this->Station->field('station_group_id', array('Station.id' => $stationLine['StationLine']['station_id']));
					$stationsInStationGroup = $this->Station->find('list', array(
						'conditions' => array('Station.station_group_id' => $stationGroupId),
						'fields' => array('Station.id')
					));
					$lineInStationGroup = $this->find('count', array(
						'conditions' => array('StationLine.station_id' => $stationsInStationGroup, 'StationLine.line_id' => $lineId)
					));
					
					if(!$inLoop && $lineInStationGroup < 2){
						$this->id = $stationLine['StationLine']['id'];
						$this->saveField('false_start', 1);
						$inLoop = true;	
					}
					if($inLoop && $lineInStationGroup > 1){
						$this->id = $stationLines[$i - 1]['StationLine']['id'];
						$this->saveField('false_end', 1);
						$inLoop = false;
					}
					
					if($stationLine['StationLine']['end']){
						if(isset($stationLines[$i + 1])){
							$this->id = $stationLines[$i + 1]['StationLine']['id'];
							$this->saveField('start', 1);
						} else {
							$this->id = $stationLines[0]['StationLine']['id'];
							$this->saveField('start', 1);
						}
					}
				}
				
				if($inLoop){
					return false;
				}
			} elseif($lineEnd == 0){
				if(count($stationLines) < 4){
					return false;
				}
				
				$this->id = $stationLines[0]['StationLine']['id'];
				$this->saveField('false_end', 1);
				$this->id = $stationLines[1]['StationLine']['id'];
				$this->saveField('false_start', 1);
				
				$this->id = $stationLines[count($stationLines) / 2]['StationLine']['id'];
				$this->saveField('false_end', 1);
				$this->id = $stationLines[count($stationLines) / 2 + 1]['StationLine']['id'];
				$this->saveField('false_start', 1);
			}
		}
		
		$this->updateAll(
			array(
				'StationLine.false_start' => 0, 
				'StationLine.false_end' => 0
			), 
			array(
				'StationLine.false_start' => 1, 
				'StationLine.false_end' => 1
			)
		);//fix cases where a station doesn't have a pair on the opposite side and gets computed both as false_start and false_emd
		
		return true;
	}
	
	public function getCoordsForAll(){
		$stationLines = $this->find('all', array(
			'fields' => array('StationLine.id', 'StationLine.station_id'),
			'contain' => array(
				'Station' => array(
					'fields' => array('Station.lat', 'Station.lng')
				)
			)
		));
		
		$coords = array();
		foreach($stationLines as $stationLine){
			$coords[$stationLine['StationLine']['id']] = array(
				'lat' => $stationLine['Station']['lat'], 
				'lng' => $stationLine['Station']['lng']
			);
		}
		
		return $coords;
	}
	
	/**
	 * Formats all station lines so that they are
	 * more readable.
	 *
	 * This method should be used when an input that
	 * performs search actions needs to be populated
	 * with station lines. Each station line is formatted like:
	 * [line_name] | [station_name] ([station_direction])
	 */
	public function formatStationLines(){
		$format = '%s | %s (%s)';
		
		$stationLines = $this->find('all', array(
			'fields' => array('StationLine.id', 'StationLine.station_id', 'StationLine.line_id'), 
			'contain' => array(
				'Station' => array(
					'fields' => array('Station.id', 'Station.name', 'Station.direction'),
				),
				'Line' => array(
					'fields' => array('Line.id', 'Line.name'),
				)
			)
		));
		
		$station_line_list = array();
		foreach ($stationLines as $stationLine) {
			$station_line_list[] = sprintf($format, $stationLine['Line']['name'], $stationLine['Station']['name'], $stationLine['Station']['direction']);
		}
		
		return $station_line_list;
	}
	
	/**
	 * Retrieves a station_line_id based
	 * on $name, which looks like:
	 * [line_name] | [station_name] ([station_direction])
	 *
	 * Useful to decrypt the human-readable
	 * station line (used for typeaheads)
	 * to a code friendly id.
	 */
	public function idFromStationLineName($name = null){
		if (
			is_null($name) ||
			!strpos($name, ' | ') ||
			!strpos($name, ' (') ||
			!strpos($name, ')')
		) {
			return false;
		}
		
		list($line, $station) = explode(' | ', $name);
			
		$station_id = $this->Station->field('id', array('name_direction' => $station));
		$line_id = $this->Line->field('id', array('name' => $line));
				
		if ($station_id && $line_id){
			return $this->field('id', array('station_id' => $station_id, 'line_id' => $line_id));
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
	
	protected function _logWrite($type, $message){
		CakeLog::write($type, $message);
	}
}
