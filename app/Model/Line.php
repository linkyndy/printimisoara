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
		'importance' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Trebuie sa specifici importanta aceastei linii!',
				'last' => true,
			),
			'inList' => array(
				// 0 - major line; 1 - normal line; 2 - minor line; 3 - occasional line
				'rule' => array('inList', array('0', '1', '2', '3')),
				'message' => 'Importanta liniei este invalida!',
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
	public $hasOne = array(
		'Coverage' => array(
			'className' => 'Coverage',
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
	);
	
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
	
	public function computeCoverage($lineId = null) {
		if (!$this->exists($lineId)) {
			return false;
		}
		
		// Fetch line data
		$this->recursive = -1;
		$line = $this->read(null, $lineId);
		
		// Fetch the line's stations
		$stations = $this->StationLine->find('list', array(
			'fields' => array('StationLine.id', 'StationLine.station_id'),
			'conditions' => array('StationLine.line_id' => $lineId),
			'order' => 'StationLine.order ASC',
		));
		
		// Don't go further if line has no stations defined
		if (empty($stations)) {
			return false;
		}
		
		// Number of covered hours for stations for each day
		$covered = array('L' => 0, 'LV' => 0, 'S' => 0, 'D' => 0);
		
		// Go through all line's stations
		foreach ($stations as $stationId) {
			// Fetch times for each line's station
			$times = $this->Time->find('all', array(
				'fields' => array('Time.id', 'Time.station_id', 'Time.line_id', 'Time.time', 'Time.day', 'Time.type', 'Time.occurances'),
				'conditions' => array('Time.station_id' => $stationId, 'Time.line_id' => $lineId),
				'order' => 'Time.time ASC',
			));
			
			// Skip stations with no times
			if (empty($times)) {
				continue;
			}
			
			// Initiate the array that stores times' day and hour
			$dayAndHours = array();
			for ($i = 0; $i <= 23; $i++) {
				foreach (array('L', 'LV', 'S', 'D') as $day) {
					$dayAndHours[$day][$i] = 0;
				}
			}
			
			// Add times to the previously initiated array
			foreach ($times as $time) {
				$dayAndHours[$time['Time']['day']][date('G', strtotime($time['Time']['time']))]++;
			}
			
			// Check whether the number of times/hour (or day) defined by
			// this line's importance is achieved or not by this station
			if ($line['Line']['importance'] == 3) {
				foreach (array('L', 'LV', 'S', 'D') as $day) {
					if (array_sum($dayAndHours[$day]) >= 4) {
						$covered[$day]++;
					}
				}
			} else {
				foreach ($dayAndHours as $day => $hours) {
					foreach ($hours as $hour) {
						if (
							$line['Line']['importance'] == 0 && $hour >= 5 ||
							$line['Line']['importance'] == 1 && $hour == 4 ||
							$line['Line']['importance'] == 2 && $hour >= 2 && $hour <= 3
						) {
							$covered[$day]++;
						}
					}
				}
			}
		}
		
		// Compute coverage for each day
		$coverage = array('L' => 0, 'LV' => 0, 'S' => 0, 'D' => 0);
		foreach ($covered as $day => $hoursCovered) {
			// Coverage for a day for a specific line is achieved by dividing
			// the total numbers of hours covered from all stations to the
			// number of valid hours, 18, and to the number of stations.
			// We multiply by 100 to get a percent score.
			$coverage[$day] = round(($hoursCovered / 18 / count($stations) * 100), 2);
		}
		$coverage['global'] = round((array_sum($coverage) / count($coverage)), 2);
		
		// Check to see whether a record for this line already exists
		if ($coverageId = $this->Coverage->field('id', array('line_id' => $lineId))) {
			$this->Coverage->id = $coverageId;
		}
		
		// Save or update the new coverage
		return $this->Coverage->save(array(
			'line_id' => $lineId,
			'coverage' => $coverage['global'],
			'coverage_L' => $coverage['L'],
			'coverage_LV' => $coverage['LV'],
			'coverage_S' => $coverage['S'],
			'coverage_D' => $coverage['D'],
		));
	}
}
