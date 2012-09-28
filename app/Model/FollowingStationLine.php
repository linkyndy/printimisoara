<?php
App::uses('AppModel', 'Model');

class FollowingStationLine extends AppModel {

	public $validate = array(
		'reference_station_line_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'ID invalid!',
			),
		),
		'station_line_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'ID invalid!',
			),
		),
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
		'order' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Ordine invalida!',
			),
		),
	);

	public $belongsTo = array(
		'StationLine' => array(
			'className' => 'StationLine',
			'foreignKey' => 'station_line_id',
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
		)
	);
	
	public function compute($lineIds = array()){
		if(!is_array($lineIds)){
			$this->_logInvalidLineArray();
			return false;	
		}
		$save = array();
		
		foreach($lineIds as $lineId){
			$stationLines = $this->StationLine->find('all', array(
				'conditions' => array('StationLine.line_id' => $lineId),
				'fields' => array('StationLine.id', 'StationLine.station_id', 'StationLine.line_id', 'StationLine.end'),
				'contain' => array(
					'Station' => array(
						'fields' => array('Station.id', 'Station.node')
					)
				),
				'order' => 'StationLine.order ASC'
			));
			
			for($i = 0; $i < count($stationLines); $i++){
				$order = 1;
				
				$referenceStationLine = array_shift($stationLines);
				
				if(!$referenceStationLine['StationLine']['end']/* && !$referenceStationLine['Station']['node']*/){
					foreach($stationLines as $stationLine){
						$save[] = array('FollowingStationLine' => array(
							'reference_station_line_id' => $referenceStationLine['StationLine']['id'],
							'station_line_id' => $stationLine['StationLine']['id'],
							'station_id' => $stationLine['StationLine']['station_id'],
							'line_id' => $stationLine['StationLine']['line_id'],
							'order' => $order++
						));
						if($stationLine['StationLine']['end'] || $stationLine['Station']['node']){
							break;	
						}
					}
				}
				
				array_push($stationLines, $referenceStationLine);
			}
		}
		
		$this->query('TRUNCATE '.$this->useTable);
		return $this->saveMany($save);
	}
	
	protected function _logInvalidLineArray(){
		$type = 'warning';
		$message = 'Functia care genereaza statiile urmatoare nu a fost apelata cu un array de linii';
		$this->_logWrite($type, $message);
	}
	
	protected function _logWrite($type, $message){
		CakeLog::write($type, $message);
	}
}
