<?php

App::uses('AppModel', 'Model');

class Time extends AppModel {

	public $displayField = 'time';

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
		'time' => array(
			'time' => array(
				'rule' => array('time'),
				'message' => 'Ora invalida!',
			),
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
		'type' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Trebuie sa alegi un tip de timp!',
				'last' => true,
			),
			'inlist' => array(
				'rule' => array('inlist', array('M', 'G', 'U', 'T')),
				'message' => 'Tip de timp invalid!',
			),
		),
		'occurances' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Numar invalid!',
			),
		),
	);

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
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
	
	/**
	 * Station line data of the currently
	 * fetched time 
	 */
	public $stationLine = array();
	
	/**
	 * HTML fetched containing times to be parsed
	 */
	protected $_html = null;
		
	/**
	 * Currently processed times for saving
	 */
	protected $_times = array();
	
	/**
	 * Methods in which times arrays can be parsed
	 */
	protected $_methods = array(
		'fetch',
		'station_line_batch',
		'line_follow',
		'station_group_follow',
		'gps'
	);
	
	/**
	 * Flag reflecting whether the current iteration
	 * in fetch recursive is the first one or not
	 */
	protected $_fetchRecursiveFirstIteration = true;
	
	/*
	 * Fetch times for a specific station line
	 *
	 * @param $stationLineId
	 *   The station_line_id for which to fetch times
	 *
	 * @return mixed
	 *   Times if they were successfully fetched and parsed,
	 *   false otherwise
	 */
	public function fetchTimes($stationLineId = null){
		if (!$this->_loadStationLine($stationLineId)) {
			return false;
		}
		
		if (!$this->_html = file_get_contents(sprintf(
			Configure::read('Ratt.url'), 
			$this->stationLine['Line']['id_ratt'], 
			$this->stationLine['Station']['id_ratt']
		))) {
			$this->_logFileNotOpened();
			return false;
		}
		
		if (!$this->_parseTimes()) {
			$this->_logFileChanged();
			return false;
		}
		
		return $this->_times;
	}
	
	/*
	 * Parse times from $this->_html
	 *
	 * @return bool
	 *   Whether the HTML file has been successfully
	 *   parsed or not
	 */
	protected function _parseTimes(){
		$html = new DOMDocument();
		@$html->loadHTML($this->_html);
		$fonts = $html->getElementsByTagName('font');
		if(empty($fonts) && $fonts->item(1)){
			return false;
		}
		
		$font = $fonts->item(1)->nodeValue;
		$font = str_replace(' min.', 'min', $font);
		$font = explode(' ', $font);
		if (
			$font[0] == 'Sosire1:' && 
			($font[1] == '>>' || $font[1] == '**:**' || strtotime($font[1])) && 
			$font[2] == 'Sosire2:' && 
			($font[3] == '**:**' || strtotime($font[3]))
		){
			$this->_times = array(
				array(
					'time' => 
						($font[1] == '>>') ? 
						time() : 
						(
							($font[1] == '**:**') ?
							null :
							(
								($this->_dayChanged($font[1])) ? 
								strtotime('+1day '.$font[1]) : 
								strtotime($font[1])
							)
						),
					'type' => ($font[1] == '>>' || strstr($font[1], 'min')) ? 'M' : 'G'
				),
				array(
					'time' => 
						($font[3] == '**:**') ?
						null :
						(
							($this->_dayChanged($font[3])) ? 
							strtotime('+1day '.$font[3]) : 
							strtotime($font[3])
						),
					'type' => (strstr($font[3], 'min')) ? 'M' : 'G'
				)
			);
			return true;
		} else {
			return false;	
		}
	}
	
	/**
	 * Save batch of times
	 *
	 * @param $method
	 *   String representing the method in which
	 *   the provided times should be processed
	 * @param $times
	 *   Array of times, specific to $method
	 *
	 * @return bool
	 *   Whether the times have been saved or not
	 */
	public function saveTimes($method = 'fetch', $times = array()){
		if (!in_array($method, $this->_methods)) {
			return false;
		}
		
		if (!empty($times)) {
			$this->_processTimes($method, $times);
		} elseif (!empty($this->_times)) {
			$this->_processTimes($method, $this->_times);
		} else {
			return false;
		}
		
		if (count($this->_times) > 1) {
			$return = ($this->saveMany($this->_times)) ? $this->_times : false;	
		} elseif(count($this->_times) == 1) {
			$return = ($this->save(array('Time' => $this->_times[0]))) ? $this->_times : false;
		} else {
			$return = array();
		}
		
		if ($return !== false) {
			$this->_logTimeSaved();
		} else {
			$this->_logTimeNotSaved();
		}
		
		return $return;
	}
	
	/**
	 * Process batch of times
	 *
	 * @param $method
	 *   String representing the method in which
	 *   the provided times should be processed
	 * @param $times
	 *   Array of times, specific to $method
	 */
	protected function _processTimes($method, $times){
		$callback = '_process' . ucfirst(Inflector::camelize($method)) . 'Times';
		$times = $this->$callback($times);
		
		if (!empty($times)) {
			foreach($times as &$time){
				$time['station_id'] = $this->stationLine['Station']['id'];
				$time['line_id'] = $this->stationLine['Line']['id'];
				$time['day'] = isset($time['day']) ? $time['day'] : $this->_dayType($time['time']);
				$time['time'] = $this->_formatTime($time['time']);
				if($occurances = $this->_timeOccurances($time['time'], $time['day'], $time['type'])){
					$time['id'] = $occurances['Time']['id'];
					$time['occurances'] = $occurances['Time']['occurances'] + 1;
				}
			}
			unset($time);
		}
		
		$this->_times = $times;
	}
	
	/**
	 * Callback for processing fetch times 
	 *
	 * @param $times
	 *   Times to be processed
	 *
	 * @return array
	 *   Array of processed times
	 *
	 * @see $this->_processTimes()
	 */
	protected function _processFetchTimes($times){
		// Unset null times (e.g.: **:**)
		foreach($times as $i => $time){
			if (is_null($time['time'])) {
				unset($times[$i]);	
			}
		}
		
		// If 'G' type times are very close (<5min),
		// keep only the first one
		if (
			count($times) == 2 &&
			count(Hash::extract($times, '{n}[type=G]')) == 2 &&
			$times[1]['time'] - $times[0]['time'] < 5 * MINUTE
		) {
			unset($times[1]);
		}
		
		// If first time is far from current time
		// (>30min), don't keep the times
		if(
			count($times) > 0 &&
			$times[0]['time'] - time() > 30 * MINUTE
		){
			$times = array();
		}
		
		return $times;
	}
	
	/**
	 * Callback for processing station_line_batch times 
	 *
	 * @param $times
	 *   Times to be processed
	 *
	 * @return array
	 *   Array of processed times
	 *
	 * @see $this->_processTimes()
	 */
	protected function _processStationLineBatchTimes($times){
		$return = array();
		
		if (
			!isset($times['Time']) ||
			!isset($times['Time']['station_line']) ||
			!isset($times['Time']['day']) ||
			!isset($times['Time']['type']) || 
			!isset($times['Time']['time']) ||
			!$this->_loadStationLine($this->Station->StationLine->idFromStationLineName($times['Time']['station_line'])) ||
			!in_array($times['Time']['day'], array('L', 'LV', 'S', 'D')) ||
			count($times['Time']['time']) != 24
		) {
			return array();
		}
	
		foreach ($times['Time']['time'] as $hour => $time) {
			if (!isset($time['minutes'])) {
				return array();
			}
			
			if (empty($time['minutes'])) {
				continue;	
			}
			
			foreach (explode(' ', $time['minutes']) as $minute) {
				if (
					!is_numeric($minute) ||
					strtotime($hour . ':' . $minute) === false
				) {
					return array();
				}
				
				$return[] = array(
					'time' => strtotime($hour . ':' . $minute),
					'day' => $times['Time']['day'],
					'type' => $times['Time']['type'],
				);
			}
		}
		
		// Clear previously defined table times
		if (
			count($return) > 0 && 
			$return[0]['type'] == 'T'
		) {
			$this->deleteAll(array(
				'Time.station_id' => $this->stationLine['Station']['id'],
				'Time.line_id' => $this->stationLine['Line']['id'],
				'Time.day' => $return[0]['day'],
				'Time.type' => 'T',
			));
		}
		
		return $return;
	}
	
	/**
	 * Callback for processing GPS times 
	 *
	 * @param $times
	 *   Times to be processed
	 *
	 * @return array
	 *   Array of processed times
	 *
	 * @see $this->_processTimes()
	 */
	protected function _processGpsTimes($times){
		if (
			!$this->_loadStationLine($times['station_line_id']) ||
			strtotime($times['time']) === false
		) {
			$times = array();
			break;
		}
		
		return array(
			array(
				'time' => strtotime($times['time']),
				'type' => 'U',
			),
		);
	}
	
	/**
	 * Get time for a specific station line,
	 * optionally filtered by time and day
	 *
	 * @param $options
	 *   Options array, can contain thw following:
	 *   - $time around which to return the time
	 *   - $day of the time
	 */
	public function getTime($stationLineId = null, $options = array()){
		if (!$this->_loadStationLine($stationLineId)) {
			return false;
		}
		
		$defaults = array(
			'time' => time(),
			'day' => $this->_dayType(isset($options['time']) ? $options['time'] : time()),
		);
		$options += $defaults;
		
		if ($this->_canFetchRecursive($options)) {
			$this->_fetchRecursive($stationLineId, $options['time']);
		}
	}
	
	/**
	 * Check whether times can be fetched recursively
	 *
	 * Basically, times can be fetched recursively if
	 * there is at least one M-type time which is later
	 * than the desired time.
	 *
	 * @param $options
	 *   Array of options passed from $this->getTime()
	 *
	 * @return bool
	 *   Whether times can be fetched recursively or not
	 *
	 * @see $this->getTime()
	 */
	protected function _canFetchRecursive($options){
		$stationLineId = $this->stationLine['StationLine']['id'];
		
		if ($this->fetchTimes($stationLineId)) {
			$this->saveTimes();
			
			if (!empty($this->_times)) {
				$mTypes = Hash::extract($this->_times, '{n}[type=M]');
				if (!empty($mTypes)) {
					$mTypesSorted = Hash::sort($mTypes, '{n}.time', 'asc');
					$time = strtotime($mTypesSorted[0]['time']);
					
					return $time; 
					if ($time > $refTime) {
						$nextStationLineId = $this->Station->StationLine->FollowingStationLine->one($stationLineId);
						$this->_fetchRecursive($nextStationLineId, $time);
					}
				}
			}
		}
	}
	
	/**
	 * Get optimized time for a specific
	 * station_line_id
	 *
	 * The time fetched with getTime() gets
	 * compared to data from the database
	 * and filtered to some algorithms before
	 * it is returned.
	 *
	 * $options array can contain:
	 * - $time around which to return the time
	 * - $day of the time
	 */
	public function getOptimizedTime($stationLineId = null, $options = array()){
		if (!$this->stationLine = $this->Line->StationLine->find('first', array(
			'conditions' => array('StationLine.id' => $stationLineId)
		))){
			return false;
		}
		
		$defaults = array(
			'time' => time(),
			'day' => $this->_dayType(time()),
		);
		$options += $defaults;
		
		if (!$this->getTime($stationLineId)) {
			$this->_logRattNotFetched();
			return false;
		}
		
		$this->_fetchDbTimes($options);
		
		if (!$this->_optimizeTimes()) {
			$this->_logOptimizeFail();
			return false;
		}
		
		return true;
	}
	
	protected function _fetchDbTimes($options){
		$this->recursive = -1;
		
		// Just-fetched times from RATT are
		// already saved in the database
		$this->_time = array();
		
		// Fetch first G or T type time which is 
		// greater or equal than requested time.
		// Skip to midnight if none is found until then
		$midnight = false;
		do	{
			$first = $this->find('first', array(
				'conditions' => array(
					'Time.station_id' => $this->stationLine['Station']['id'],
					'Time.line_id' => $this->stationLine['Line']['id'],
					'Time.time >=' =>  
						($midnight === true) ? 
							$this->_formatTime('midnight') : 
							$this->_formatTime($options['time'])
					,
					'Time.day' => $options['day'],
					'Time.type' => array('G', 'T'),
				),
				'order' => 'Time.time ASC',
			));
		} while(
			empty($first) &&
			$midnight === false &&
			$midnight = true
		);
		$this->_time[] = $first['Time'];
		
		// Fetch M or U type times which are within
		// a specific minute-radius from the time above
		$midnight = false;
		do	{
			$sooner = $this->find('all', array(
				'conditions' => array(
					'Time.station_id' => $this->stationLine['Station']['id'],
					'Time.line_id' => $this->stationLine['Line']['id'],
					'Time.time <=' => 
						($midnight === true) ? 
							$this->_formatTime('midnight', '-1second') : 
							$this->_formatTime($first['Time']['time'])
					,
					'Time.time >=' => 
						($midnight === true) ?
							$this->_formatTime('midnight', '-1second-3minutes') :
							(
								(strtotime($first['Time']['time'].'-3minutes') > $options['time']) ?
								$this->_formatTime($first['Time']['time'], '-3minutes') :
								$this->_formatTime($options['time'])
							)
					,
					'Time.day' => $options['day'],
					'Time.type' => array('M', 'U'),
				),
			));
		} while(
			empty($sooner) &&
			$midnight === false &&
			$midnight = true
		);
		foreach ($sooner as $time) {
			$this->_time[] = $time['Time'];
		}
		
		$midnight = false;
		do	{
			$later = $this->find('all', array(
				'conditions' => array(
					'Time.station_id' => $this->stationLine['Station']['id'],
					'Time.line_id' => $this->stationLine['Line']['id'],
					'Time.time >' => 
						($midnight === true) ? 
							$this->_formatTime('midnight') : 
							$this->_formatTime($first['Time']['time'])
					,
					'Time.time <=' => 
						($midnight === true) ? 
							$this->_formatTime('midnight', '+3minutes') : 
							$this->_formatTime($first['Time']['time'], '+3minutes')
					,
					'Time.day' => $options['day'],
					'Time.type' => array('M', 'U'),
				),
			));
		} while(
			empty($later) &&
			$midnight === false &&
			$midnight = true
		);
		foreach ($later as $time) {
			$this->_time[] = $time['Time'];
		}
	}
	
	protected function _optimizeTimes(){
		$this->_time = Hash::sort($this->_time, '{n}.time', 'asc');
		
		// Map minutes to indexes to ease 
		// finding the optimized time
		foreach ($this->_time as $i => &$time) {
			($i == 0) ?
				$time['index'] = 0 :
				$time['index'] = $this->_time[$i - 1]['index'] + (int)date('i', strtotime($time['time']) - strtotime($this->_time[$i - 1]['time']));
		}
		unset($time);
		
		$type_weights = array(
			'G' => 0.7,
			'M' => 0.9,
			'U' => 0.5,
			'T' => 0.65,
		);
		
		$occurance_weights = array(
			'G' => 0.1,
			'M' => 0.75,
			'U' => 0.9,
			'T' => 0.05,
		);
		
		$optimized_index = array('sum' => 0, 'weights' => 0);
		foreach ($this->_time as $time) {
			$optimized_index['sum'] += 
				$time['index'] * 
				$type_weights[$time['type']] * 
				$time['occurances'] * 
				$occurance_weights[$time['type']]
			;
			$optimized_index['weights'] += 
				$type_weights[$time['type']] * 
				$time['occurances'] * 
				$occurance_weights[$time['type']]
			;
		}
		$optimized_index = round($optimized_index['sum'] / $optimized_index['weights']);
		
		foreach ($this->_time as $time) {
			if ($time['index'] == $optimized_index) {
				$this->optimizedTime = $time;
				return true;
			}
		}
		
		return false;
	}
	
	/**
	 * Retrieves all times for a station
	 *
	 * @param $station_id
	 *   ID of the station
	 *
	 * @return array
	 *   Array of times, grouped by line,
	 *   day, hour and minute
	 */	
	public function station($station_id = null){
		$this->Station->id = $station_id;
		if (!$this->Station->exists()) {
			throw new NotFoundException(__('Statie invalida'));
		}
		
		$lines = $this->Station->StationLine->find('list', array(
			'fields' => array('StationLine.id', 'StationLine.line_id'),
			'conditions' => array('StationLine.station_id' => $station_id),
		));
		
		$station = array();
		foreach ($lines as $line_id){
			$times = $this->find('all', array(
				'conditions' => array(
					'Time.station_id' => $station_id,
					'Time.line_id' => $line_id,
				),
				'order' => 'Time.time ASC',
			));
			if (isset($times[0]['Line'])) {
				$station[] = array(
					'Line'  => $times[0]['Line'],
					'Time' => $this->_groupByHoursAndDay($times),
				);
			}
		}
		
		return $station;
	}
	
	/**
	 * Retrieves all times for a line
	 *
	 * @param $line_id
	 *   ID of the line
	 *
	 * @return array
	 *   Array of times, grouped by direction,
	 *   station, day, hour and minute
	 */	
	public function line($line_id = null){
		$this->Line->id = $line_id;
		if(!$this->Line->exists()){
			throw new NotFoundException(__('Linie invalida'));
		}
		
		$directions = $this->Line->directions($line);
		foreach($directions as &$direction){
			foreach($direction as &$station){
				$times = $this->find('all', array('conditions' => array('Time.station_id' => $station['Station']['id'], 'Time.line_id' => $line), 'order' => 'Time.time ASC'));
				$station['Time'] = $this->_groupByHoursAndDay($times);	
			}
		}
		return $directions;
	}
	
	/**
	 * Formats an array of times by hour and day
	 */
	protected function _groupByHoursAndDay($times){
		$hoursAndDay = array();
		for($i = 0; $i <= 23; $i++){
			foreach(array('L', 'LV', 'S', 'D') as $day){
				$hoursAndDay[$i][$day] = array();
			}
		}
		
		foreach($times as $time){
			$hoursAndDay[date('G', strtotime($time['Time']['time']))][$time['Time']['day']][] = $time['Time'];
		}
		
		return $hoursAndDay;
	}
	
	/**
	 * Find the time coverage on all lines
	 *
	 * See how consistent is the time database
	 * by checking how many times are saved for
	 * each line, for each type of day.
	 *
	 * At the end we have 3 metrics: coverage
	 * score (0 to 2.11), coverage percent
	 * (0% to 100%) and coverage mark (high,
	 * good, average and poor).
	 */
	public function coverage(){
		$this->recursive = 0;
		
		$lines = $this->Line->find('all', array(
			'fields' => array('Line.id', 'Line.name', 'Line.colour'), 
			'order' => 'Line.name ASC',
			'contain' => array(
				'StationLine' => array(
					'fields' => array('StationLine.id', 'StationLine.line_id', 'StationLine.station_id'),
				),
			),
		));
		
		$global_coverage = array();
		
		foreach ($lines as &$line) {
			$stations = Hash::extract($line, 'StationLine.{n}.station_id');
			
			// Only check coverage for lines that have
			// stations defined
			if (empty($stations) || count($stations) == 0) {
				continue;
			}
			
			foreach ($stations as $station) {
				$times = $this->find('all', array(
					'fields' => array('Time.id', 'Time.time', 'Time.day'),
					'conditions' => array(
						'Time.line_id' => $line['Line']['id'],
						'Time.station_id' => $station),
					'order' => 'Time.time ASC',
				));
				
				// Skip stations with no times
				if (empty($times) || count($times) == 0) {
					foreach(array('L', 'LV', 'S', 'D') as $day){
						$line['Line']['coverage_score'][$day][] = 0;
						$line['Line']['coverage_percent'][$day][] = 0;
					}
					
					continue;
				}
				
				$dayAndHours = array();
				for($i = 0; $i <= 23; $i++){
					foreach(array('L', 'LV', 'S', 'D') as $day){
						$dayAndHours[$day][$i] = 0;
					}
				}
				
				foreach ($times as $time) {
					$dayAndHours[$time['Time']['day']][date('G', strtotime($time['Time']['time']))]++;
				}
				
				// Coverage is rated on a scale from 0 (none)
				// to 2 (good), where at least 2 times/hour
				// means good.
				$coverage = array('L' => 0, 'LV' => 0, 'S' => 0, 'D' => 0);
				foreach ($dayAndHours as $day => $hours) {
					foreach ($hours as &$hour) {
						if ($hour > 2) {
							$coverage[$day] += 2;
						} elseif ($hour > 1) {
							$coverage[$day] += 1;
						}
					}
					
					// Coverage score for one day is obtained
					// by dividing the coverage points to the
					// number of valid hours, 18.
					$line['Line']['coverage_score'][$day][] = $coverage[$day] / 18;
					
					// Coverage percent, how much % from the
					// maximum coverage score, 2.11
					$line['Line']['coverage_percent'][$day][] = 100 * ($coverage[$day] / 18) / 2.11;
				}
			}
			
			// Line coverage score and percent from
			// its station data
			foreach (array('L', 'LV', 'S', 'D') as $day) {
				$line['Line']['coverage_score'][$day] = round(
					array_sum($line['Line']['coverage_score'][$day]) / 
					count($line['Line']['coverage_score'][$day])
				, 2);
				$line['Line']['coverage_percent'][$day] = round(
					array_sum($line['Line']['coverage_percent'][$day]) / 
					count($line['Line']['coverage_percent'][$day])
				, 2);
				
				// Coverage mark is obtained as follows:
				// - high: above 2.10
				// - good: above 1.87
				// - average: above 1.37
				// - poor: below 1.37
				if ($line['Line']['coverage_score'][$day] > 2.10) {
					$line['Line']['coverage_mark'][$day] = 'high';
				} elseif ($line['Line']['coverage_score'][$day] > 1.87) {
					$line['Line']['coverage_mark'][$day] = 'good';
				} elseif ($line['Line']['coverage_score'][$day] > 1.37) {
					$line['Line']['coverage_mark'][$day] = 'average';
				} else {
					$line['Line']['coverage_mark'][$day] = 'poor';
				}
				
				$global_coverage[] = $line['Line']['coverage_percent'][$day];
			}
				
			// Weight for days, depending on vacation
			// Useful for more accurate general scores
			if (Configure::read('Config.is_vacation') === true) {
				$day_weights = array(
					'L' => 0,
					'LV' => 0.6,
					'S' => 0.25,
					'D' => 0.15,
				);
			} else {
				$day_weights = array(
					'L' => 0.65,
					'LV' => 0,
					'S' => 0.2,
					'D' => 0.15,
				);
			}
			
			// General coverage for each line
			$line['Line']['coverage_score']['general'] = 0;
			$line['Line']['coverage_percent']['general'] = 0;
			foreach(array('L', 'LV', 'S', 'D') as $day) {
				$line['Line']['coverage_score']['general'] += $line['Line']['coverage_score'][$day] * $day_weights[$day];
				$line['Line']['coverage_percent']['general'] += $line['Line']['coverage_percent'][$day] * $day_weights[$day];
			}
			$line['Line']['coverage_score']['general'] = round(
				$line['Line']['coverage_score']['general'] / 
				array_sum($day_weights)
			, 2);
			$line['Line']['coverage_percent']['general'] = round(
				$line['Line']['coverage_percent']['general'] / 
				array_sum($day_weights)
			, 2);
		}
		
		// Find global coverage percent bearing in mind
		// that the $global_coverage array can be empty
		$global_coverage_percent = round(array_sum($global_coverage) / max(1, count($global_coverage)), 2);

		return compact('lines', 'global_coverage_percent');
	}
		
	
	/**
	 * Utility functions
	 */
	 
	protected function _loadStationLine($stationLineId){
		$this->Line->StationLine->recursive = 0;
		
		if (!$this->stationLine = $this->Line->StationLine->find('first', array(
			'conditions' => array('StationLine.id' => $stationLineId)
		))) {
			return false;
		}
		
		return true;
	}
	
	protected function _dayChanged($time){
		if (
			date('G', strtotime($time)) == 
			date('G', strtotime(Configure::read('Config.day_change')))
		) {
			return true;
		}
		
		return false;
	}
	
	protected function _timeOccurances($time, $day, $type){
		return $this->find('first', array(
			'fields' => array('Time.id', 'Time.occurances'),
			'conditions' => array(
				'Time.station_id' => $this->stationLine['Station']['id'],
				'Time.line_id' => $this->stationLine['Line']['id'],
				'Time.time' => $this->_formatTime($time),
				'Time.day' => $day,
				'Time.type' => $type,
			),
			'contain' => false
		));
	}
	
	protected function _dayType($time){
		$day = date('w', $time);
		if($day >= 1 && $day <= 5){
			if(
				$time >= strtotime(Configure::read('Config.vacation_start')) && 
				$time <= strtotime(Configure::read('Config.vacation_end'))
			){
				return 'LV';
			}
			return 'L';
		} elseif($day == 6){
			return 'S';
		} else{
			return 'D';	
		}
	}
	
	/**
	 * Formats any string of $time to
	 * a valid database $format, optionally
	 * including a $relative string
	 */
	protected function _formatTime($time, $relative = null){
		$format = 'H:i';
		
		if (is_numeric($time)) {
			return (!is_null($relative)) ? 
				date($format, strtotime(date($format, (int)$time) . $relative)) : 
				date($format, (int)$time);
		} else {
			if ($time === date($format, strtotime($time))) {
				return (!is_null($relative)) ? 
					date($format, strtotime($time . $relative)) : 
					$time;
			} else {
				return (!is_null($relative)) ?
					date($format, strtotime($time . $relative)) : 
					date($format, strtotime($time));
			}
		}
	}
	
	/**
	 * Cron functions
	 */
	
	/**
	 * Fetch times for a specific station line.
	 * 
	 * @param $stationLineId
	 *   ID of the station line; if ommited, times
	 *   for a random station line will be fetched.
	 * @param $uncovered
	 *   Whether times should be fetched for an
	 *   uncovered line or not.
	 */
	public function fetchStationLineTimes($stationLineId = null, $uncovered = true){
		if (is_null($stationLineId)){
			$uncoveredLines = Configure::read('Maintenance.uncovered_lines');
			
			if (
				$uncovered === true &&
				!empty($uncoveredLines)
			){
				$stationLineId = $this->Station->StationLine->field('id', array('line_id' => array_rand($uncoveredLines)), 'rand()');
			} else {
				$stationLineId = $this->Station->StationLine->field('id', array(), 'rand()');
			}
			
		}
		
		if ($this->fetchTimes($stationLineId)) {
			return $this->saveTimes();
		}
		
		return false;
	}
	
	/**
	 * Fetch times for a specific line.
	 * 
	 * @param $lineId
	 *   ID of the line; if ommited, times
	 *   for a random line will be fetched.
	 * @param $uncovered
	 *   Whether times should be fetched for an
	 *   uncovered line or not.
	 */
	public function fetchLineTimes($lineId = null, $uncovered = true){
		$this->Station->StationLine->recursive = 0;
		
		if (is_null($lineId)){
			$uncoveredLines = Configure::read('Maintenance.uncovered_lines');
			
			if (
				$uncovered === true &&
				!empty($uncoveredLines)
			){
				$lineId = array_rand($uncoveredLines);
			} else {
				$lineId = $this->Line->field('id', array(), 'rand()');
			}
		}
		
		$stationLines = $this->Station->StationLine->find('all', array(
			'conditions' => array('StationLine.line_id' => $lineId),
			'order' => 'StationLine.order ASC',
		));
		
		if (empty($stationLines)){
			$this->_logStationLinesNotFound($lineId);
			return false;
		}
		
		$times = array();
		foreach ($stationLines as $stationLine) {
			if ($this->fetchTimes($stationLine['StationLine']['id'])) {
				$time = $this->saveTimes();

				if ($time === false){
					// Message is logged from $this->saveTimes() method
					return false;
				}
				
				$times[] = $time;
			}
		}
		
		// Save distances between stations
		if (!$this->Station->StationDistance->saveFromTimes($times)) {
			$this->_logDistancesFail();
			return false;
		}
		
		return true;
	}
	
	
	/**
	 * Log methods
	 */
	
	protected function _logFileNotOpened(){
		$type = 'warning';
		$message = 'Fisierul de la RATT nu a putut fi deschis (<code>'.sprintf(Configure::read('Ratt.url'), $this->stationLine['Line']['id_ratt'], $this->stationLine['Station']['id_ratt']).'</code>)';
		$this->_logWrite($type, $message);
	}
	
	protected function _logFileChanged(){
		$type = 'warning';
		$message = 'Este posibil ca fisierul de la RATT sa se fi schimbat deoarece acesta nu mai poate fi parsat ca pana acum. Asa arata fisierul: <pre>'.h($this->_html).'</pre><code>'.sprintf(Configure::read('Ratt.url'), $this->stationLine['Line']['id_ratt'], $this->stationLine['Station']['id_ratt']).'</code>';
		$this->_logWrite($type, $message);
	}
	
	protected function _logTimeNotSaved(){
		$type = 'warning';
		$message = 'Timpii nu au putut fi salvati pentru statia '.$this->stationLine['Station']['name_direction'].', linia '.$this->stationLine['Line']['name'].' (<code>$stationLineId = '.$this->stationLine['StationLine']['id'].'</code>).';
		$this->_logWrite($type, $message);
	}
	
	protected function _logTimeSaved(){
		$type = 'time-info';
		if(count($this->_times) > 0){
			$message = 'S-au salvat urmatorii timpi pentru statia '.$this->stationLine['Station']['name_direction'].', linia '.$this->stationLine['Line']['name'].': ';
			foreach($this->_times as $time){
				$message .= '<code>'.$time['time'].' '.$time['day'].'</code><code>'.$time['type'].'</code>.';	
			}
		} else {
			$message = 'Nu a fost niciun timp care sa trebuiasca sa fie salvat pentru statia '.$this->stationLine['Station']['name_direction'].', linia '.$this->stationLine['Line']['name'].'.';	
		}
		$this->_logWrite($type, $message);
	}
	
	protected function _logRattNotFetched(){
		$type = 'warning';
		$message = 'Timpii nu au putut fi luati de la RATT pentru optimizare pentru statia '.$this->stationLine['Station']['name_direction'].', linia '.$this->stationLine['Line']['name'].' (<code>$stationLineId = '.$this->stationLine['StationLine']['id'].'</code>)';
		$this->_logWrite($type, $message);
	}
	
	protected function _logOptimizeFail(){
		$type = 'warning';
		$message = 'Timpii nu au putut fi optimizati pentru statia '.$this->stationLine['Station']['name_direction'].', linia '.$this->stationLine['Line']['name'].' (<code>$stationLineId = '.$this->stationLine['StationLine']['id'].'</code>)';
		$this->_logWrite($type, $message);
	}
	
	protected function _logStationLinesNotFound($lineId){
		$type = 'warning';
		$message = 'Nu au putut fi returnate statiile pentru linia <code>$lineId = ' . $lineId . '</code> in vederea salvarii timpilor pentru linie.';
		$this->_logWrite($type, $message);
	}
	
	protected function _logDistancesFail(){
		$type = 'warning';
		$message = 'Distantele intre statii nu au putut fi salvate pentru linia ' . $this->stationLine['Line']['name'] . ' (<code>$lineId = ' . $this->stationLine['Line']['id'] . '</code>)';
		$this->_logWrite($type, $message);
	}
	
	protected function _logWrite($type, $message){
		CakeLog::write($type, $message);
	}
}
