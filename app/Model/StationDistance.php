<?php
App::uses('AppModel', 'Model');

class StationDistance extends AppModel {

	public $validate = array(
		'from_station_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Invalid station ID',
			),
		),
		'to_station_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Invalid station ID',
			),
		),
		'minutes' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Distanta invalida!',
			),
		),
		'time' => array(
			'time' => array(
				'rule' => array('time'),
				'message' => 'Timp invalid!'
			)
		),
		'day' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Trebuie sa alegi un tip de zi!',
				'last' => true,
			),
			'inlist' => array(
				'rule' => array('inlist', array('L', 'LV', 'S', 'D')),
				'message' => 'Tip de zi invalid!',
			),
		),
		'occurances' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Numar invalid!',
			),
		),
		// Just a helper to display a more prettier form
		'from' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Trebuie sa alegi o statie de plecare!',
			),
		),
		'to' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Trebuie sa alegi o statie de sosire!',
			),
		),
	);
	
	public $belongsTo = array(
		'FromStation' => array(
			'className' => 'Station',
			'foreignKey' => 'from_station_id',
			'conditions' => '',
			'fields' => array('FromStation.id', 'FromStation.id_ratt', 'FromStation.station_group_id', 'FromStation.name', 'FromStation.direction', 'FromStation.lat', 'FromStation.lng', 'FromStation.region'),
			'order' => ''
		),
		'ToStation' => array(
			'className' => 'Station',
			'foreignKey' => 'to_station_id',
			'conditions' => '',
			'fields' => array('ToStation.id', 'ToStation.id_ratt', 'ToStation.station_group_id', 'ToStation.name', 'ToStation.direction', 'ToStation.lat', 'ToStation.lng', 'ToStation.region'),
			'order' => ''
		)
	);
	
	/**
	 * Saves station distances from an array of times
	 * for a line
	 *
	 * @param $times
	 *   Array of times as saved with $this->Times->saveTimes()
	 *
	 * @return bool
	 *   Whether station distances have been saved or not
	 */
	public function saveFromTimes($times = array()){
		if (empty($times)){
			return true;
		}
		debug($times);
		// If there are times for each station of a line
		// Make a circular array so that distance between all
		// two consecutive stations are saved
		if (
			count($times) == 
			$this->FromStation->StationLine->find('count', array('conditions' => array('StationLine.line_id' => $times[0][0]['line_id'])))
		) {
			$times[] = $times[0];
		}
		
		$stationDistances = array();
		// Loop through each time...
		for ($i = 0; $i < count($times) - 1; $i++) {
			// Skip to next time if current is empty
			if (empty($times[$i])) {
				continue;
			}
			
			// ...and all its following times
			for ($j = $i + 1; $j < count($times); $j++) {
				// Break loop and go to next *first* time if current
				// second time is empty or
				if (empty($times[$j])) {
					break;
				}
				
				$stationDistance = array();
				// Cycle to all times between the two stations
				foreach ($times[$i] as $fromTime) {
					foreach ($times[$j] as $toTime) {
						// Check times only if they are of M type
						if (
							$fromTime['type'] != 'M' || 
							$toTime['type'] != 'M'
						){
							continue;
						}
						
						// Compute minutes between the first and the second station
						// and add them to $minutes for further reference
						$minutes = $this->_minutesBetween($fromTime['time'], $toTime['time']);

						// Save only the distances whose times are for the same vehicle 
						// TODO find a better way for checking $minutes for multiple stations ($j-$i)
						if (
							$minutes === false ||
							$minutes / ($j - $i) > Configure::read('Config.max_distance_in_minutes')
						) {
							continue;
						}

						$stationDistance = array(
							'from_station_id' => $fromTime['station_id'],
							'to_station_id' => $toTime['station_id'],
							'minutes' => $minutes,
							'time' => date('H:i'),
							'day' => $fromTime['day'],
						);

						// Occurances among currently parsed times..
						// ... occurances from database
						if ($existing = array_search($stationDistance, $stationDistances) !== false) {
							// If occurances set (from database occurances on first occurance parse)
							if (isset($stationDistances[$existing]['occurances'])) {
								$stationDistances[$existing]['occurances']++;
							} else {
								$stationDistances[$existing]['occurances'] = 2;
							}

							// Don't add the distance to the array since it already exists there
							continue;

						} elseif ($occurances = $this->_distanceOccurances($stationDistance)) {
							$stationDistance['id'] = $occurances['StationDistance']['id'];
							$stationDistance['occurances'] = $occurances['StationDistance']['occurances'] + 1;
						}

						$stationDistances[] = $stationDistance;
					}
				}
				
				// No distance has been saved, break the *second* time loop
				// and head to the next first time
				if (empty($stationDistance)) {
					break;
				}
			}
		}
		debug($stationDistances);exit;
		return (!empty($stationDistances)) ? $this->saveMany($stationDistances) : true;
	}
	
	/**
	 * Retrieves the distance in minutes between 
	 * two stations
	 * 
	 * No check on params is made because method is called
	 * from $this->Time->getTime() where all params are defined
	 * and checked.
	 *
	 * Distance in minutes represents the weighted average of the
	 * distances in minutes and their occurances
	 */
	public function minutesBetween($fromStationId, $toStationId, $options = array()){
		$this->recursive = -1;
		
		$distances = $this->find('all', array(
			'conditions' => array(
				'from_station_id' => $fromStationId, 
				'to_station_id' => $toStationId, 
				'time >=' => date('H:i', strtotime($options['time'] . '-' . Configure::read('Config.distance_range') . 'minutes')),
				'time <=' => date('H:i', strtotime($options['time'] . '+' . Configure::read('Config.distance_range') . 'minutes')),
				'day' => $options['day'],
			),
		));
		
		if (empty($distances)) {
			return false;
		}
		
		$sum = $weights = 0;
		foreach ($distances as $distance) {
			$sum += $distance['StationDistance']['minutes'] * $distance['StationDistance']['occurances'];
			$weights += $distance['StationDistance']['occurances'];
		}
		
		return round($sum / $weights);
	}
	
	/**
	 * Computes difference in minutes between two
	 * times in the H:i format
	 *
	 * If start time is greater then end time and end time
	 * is near midnight, then compute the difference with
	 * the day change
	 */
	protected function _minutesBetween($start, $end){
		$start = strtotime($start);
		
		if ($start > strtotime($end)) {
			if (date('G', strtotime($end)) == 0) {
				$end = strtotime($end . '+1day');
			} else {
				return false;
			}
		} else {
			$end = strtotime($end);
		}
		
		return (int)date('i', ($end - $start));
	}
	
	protected function _distanceOccurances($data = array()){
		return $this->find('first', array(
			'fields' => array('StationDistance.id', 'StationDistance.occurances'),
			'conditions' => array(
				'StationDistance.from_station_id' => $data['from_station_id'],
				'StationDistance.to_station_id' => $data['to_station_id'],
				'StationDistance.minutes' => $data['minutes'],
				'StationDistance.time' => $data['time'],
				'StationDistance.day' => $data['day'],
			),
			'contain' => false
		));
	}
}