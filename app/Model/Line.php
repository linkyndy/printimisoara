<?php
App::uses('AppModel', 'Model');
/**
 * Line Model
 *
 * @property Time $Time
 * @property StationLine $StationLine
 */
class Line extends AppModel {
/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'name';
/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'id_ratt' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'ID invalid!',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'name' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Trebuie sa specifici un nume pentru aceasta linie!',
				//'allowEmpty' => false,
				//'required' => false,
				'last' => true, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
			'between' => array(
				'rule' => array('between', 1, 10),
				'message' => 'Numele liniei trebuie sa contina intre 1 si 10 caractere!',
				//'allowEmpty' => false,
				//'required' => false,
				'last' => true, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
			'alphanumeric' => array(
				'rule' => array('alphanumeric'),
				'message' => 'Numele liniei trebuie sa contina doar caractere alfanumerice!',
				//'allowEmpty' => false,
				//'required' => false,
				'last' => true, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
			/*'isUnique' => array(
				'rule' => array('isUnique'),
				'message' => array('Ai mai adaugat aceasta linie pana acum!')
			)*/
		),
		'type' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Trebuie sa specifici un tip pentru aceasta linie!',
				//'allowEmpty' => false,
				//'required' => false,
				'last' => true, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
			'inList' => array(
				'rule' => array('inList', array('tv', 'tb', 'ab', 'am', 'ae')),
				'message' => 'Tipul liniei este invalid!',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'colour' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Trebuie sa specifici o culoare pentru aceasta linie!',
				//'allowEmpty' => false,
				//'required' => false,
				'last' => true, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
			'alphanumeric' => array(
				'rule' => array('alphanumeric'),
				'message' => 'Culoarea liniei trebuie sa contina doar caractere alfanumerice!',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => true, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Time' => array(
			'className' => 'Time',
			'foreignKey' => 'line_id',
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
		'ComputedTime' => array(
			'className' => 'ComputedTime',
			'foreignKey' => 'line_id',
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
		'StationLine' => array(
			'className' => 'StationLine',
			'foreignKey' => 'line_id',
			'dependent' => true,
			'conditions' => '',
			'fields' => '',
			'order' => 'StationLine.order ASC',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);
	
	public $lineEnds = array();//imported from StationLine
	public $lineCombinations = array();
	public $linePoints = array();//for 2 end lines: 0 => (Start1, End2), 1 => (Start2, End1), for 1 end lines: 0 => (Start, FalseEnd), 1 => (FalseStart, End), for 0 end lines: 0 => (FalseStart1, FalseEnd1), 1 => (FalseStart2, FalseEnd2)
	
	public function directions($id = null){
		$this->id = $id;
		if (!$this->exists()) {
			throw new NotFoundException(__('Linie invalida'));
		}
		
		$station_lines = $this->StationLine->find('all', array('conditions' => array('line_id' => $id), 'order' => 'order ASC'));
		if(empty($station_lines)){
			return array();	
		}
		
		if(in_array(true, Set::extract('/StationLine/end', $station_lines))){
			if(!$station_lines[count($station_lines) - 1]['StationLine']['end']){
				foreach($station_lines as &$station_line){//circulate array until first station after an end
					array_push($station_lines, array_shift($station_lines));
					if($station_line['StationLine']['end']){
						break;	
					}
				}		
			}
		}
		
		$stations = array();
		$direction = 0;
		foreach($station_lines as $i => $station_line){//loop through station to list all directions
			if($i > 0 && $station_lines[$i - 1]['StationLine']['end']){
				$direction++;
			}
			$stations[$direction][] = $station_line;
		}
		
		return $stations;
	}
	
	public function lineCombinations(){
		$lines = $this->find('list', array('fields' => array('Line.id')));
		
		$this->lineCombinations = $lines;
		
		foreach($lines as $firstLine){
			foreach($lines as $secondLine){
				if($firstLine != $secondLine){
					$this->lineCombinations[] = array($firstLine, $secondLine);	
				}
			}
		}
		
		foreach($lines as $firstLine){
			foreach($lines as $secondLine){
				foreach($lines as $thirdLine){
					if($firstLine != $secondLine && $secondLine != $thirdLine && $thirdLine != $firstLine){
						$this->lineCombinations[] = array($firstLine, $secondLine, $thirdLine);	
					}
				}
			}
		}
		
		return true;
	}
	
	public function linePoints(){
		if(!$this->lineEnds = $this->StationLine->lineEnds){
			return false;	
		}
		
		$lines = $this->find('list', array('fields' => array('Line.id')));
		
		foreach($lines as $lineId){
			$stationLines = $this->StationLine->find('all', array(
				'conditions' => array(
					'StationLine.line_id' => $lineId,
					'OR' => array(
						'StationLine.end' => 1,
						'StationLine.start' => 1,
						'StationLine.false_start' => 1,
						'StationLine.false_end' => 1
					)
				),
				'fields' => array('StationLine.id', 'StationLine.order', 'StationLine.end', 'StationLine.start', 'StationLine.false_start', 'StationLine.false_end'),
				'order' => 'StationLine.order ASC',
				'contain' => false
			));
			
			if(
				count($stationLines) != 4 ||
				$this->lineEnds[$lineId] == 2 && (
					count(array_keys(Set::extract('/StationLine/end', $stationLines), true)) != 2 ||
					count(array_keys(Set::extract('/StationLine/start', $stationLines), true)) != 2
				) ||
				$this->lineEnds[$lineId] == 1 && (
					count(array_keys(Set::extract('/StationLine/end', $stationLines), true)) != 1 ||
					count(array_keys(Set::extract('/StationLine/start', $stationLines), true)) != 1 ||
					count(array_keys(Set::extract('/StationLine/false_start', $stationLines), true)) != 1 ||
					count(array_keys(Set::extract('/StationLine/false_end', $stationLines), true)) != 1
				) ||
				$this->lineEnds[$lineId] == 0 && (
					count(array_keys(Set::extract('/StationLine/false_start', $stationLines), true)) != 2 ||
					count(array_keys(Set::extract('/StationLine/false_end', $stationLines), true)) != 2
				)
			){
				return false;	
			}
			
			while($stationLines[0]['StationLine']['start'] || $stationLines[0]['StationLine']['false_start']){
				array_push($stationLines, array_shift($stationLines));	
			}
			
			foreach($stationLines as $stationLine){
				if($this->lineEnds[$lineId] == 2){
					if($stationLine['StationLine']['start']){
						if(isset($this->linePoints[$lineId][1][0])){//first end is [0][1], so first start should be [1][0]
							$this->linePoints[$lineId][0][0] = $stationLine['StationLine']['id'];
						} else {
							$this->linePoints[$lineId][1][0] = $stationLine['StationLine']['id'];
						}
					} elseif($stationLine['StationLine']['end']){
						if(isset($this->linePoints[$lineId][0][1])){//first end is [0][1], so second end should be [1][1]
							$this->linePoints[$lineId][1][1] = $stationLine['StationLine']['id'];
						} else {
							$this->linePoints[$lineId][0][1] = $stationLine['StationLine']['id'];
						}
					}
				} elseif($this->lineEnds[$lineId] == 1){
					if($stationLine['StationLine']['start']){//only one start, [0][0]
						$this->linePoints[$lineId][0][0] = $stationLine['StationLine']['id'];
					} elseif($stationLine['StationLine']['end']){//only one end, [1][1]
						$this->linePoints[$lineId][1][1] = $stationLine['StationLine']['id'];
					} elseif($stationLine['StationLine']['false_start']){//only one false_start, [1][0]
						$this->linePoints[$lineId][1][0] = $stationLine['StationLine']['id'];
					} elseif($stationLine['StationLine']['false_end']){//only one false_end, [0][1]
						$this->linePoints[$lineId][0][1] = $stationLine['StationLine']['id'];
					}
				} elseif($this->lineEnds[$lineId] == 0){
					if($stationLine['StationLine']['false_start']){
						if(isset($this->linePoints[$lineId][0][0])){//first false_end is [0][1], so first false_start should be [0][0]
							$this->linePoints[$lineId][1][0] = $stationLine['StationLine']['id'];
						} else {
							$this->linePoints[$lineId][0][0] = $stationLine['StationLine']['id'];
						}
					} elseif($stationLine['StationLine']['false_end']){
						if(isset($this->linePoints[$lineId][0][1])){//first false_end is [0][1], so second false_end should be [1][1]
							$this->linePoints[$lineId][1][1] = $stationLine['StationLine']['id'];
						} else {
							$this->linePoints[$lineId][0][1] = $stationLine['StationLine']['id'];
						}
					}
				}
			}
			
			ksort($this->linePoints[$lineId][0]);
			ksort($this->linePoints[$lineId][1]);
		}
		
		return true;
	}

}
