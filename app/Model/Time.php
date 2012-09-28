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
	
	
	public $stationLine = array();
	protected $_html = null;
	
	/** 
	 * Contains times fetched from RATT with
	 * $this->getTime() or times fetched from
	 * the database with $this->getOptimizedTime()
	 */
	protected $_time = null;
	
	/**
	 * Contains times fetched from RATT for
	 * public access
	 */
	public $time = null;
	
	/**
	 * Contains optimized time for public
	 * access
	 */
	public $optimizedTime = null;
	
	/**
	 * Process the add time form and build a 
	 * proper array to be saved in the database
	 */
	public function processAddTimes($form){
		$return = array();
		
		if (!isset($form['Time'])) {
			return false;
		} elseif (
			!isset($form['Time']['station_line']) ||
			!isset($form['Time']['day']) ||
			!isset($form['Time']['type']) || 
			!isset($form['Time']['time'])
		) {
			return false;
		}
		
		if(!$this->stationLine = $this->Line->StationLine->find('first', array(
			'conditions' => array('StationLine.id' => $this->Station->StationLine->idFromStationLineName($form['Time']['station_line']))
		))){
			return false;
		}
		
		if (!in_array($form['Time']['day'], array('L', 'LV', 'S', 'D'))) {
			return false;
		}
		
		if (count($form['Time']['time']) != 24) {
			return false;
		}
		foreach ($form['Time']['time'] as $hour => $time) {
			if (!isset($time['minutes'])) {
				return false;
			}
			
			if (empty($time['minutes'])) {
				continue;	
			}
			
			foreach (explode(' ', $time['minutes']) as $minute) {
				if (!is_numeric($minute)) {
					return false;
				}
				
				$return_item = array(
					'station_id' => $this->stationLine['Station']['id'],
					'line_id' => $this->stationLine['Line']['id'],
					'time' => date('H:i', strtotime($hour . ':' . $minute)),
					'day' => $form['Time']['day'],
					'type' => $form['Time']['type'],
				);
				
				if($occurances = $this->_timeOccurances($return_item['time'], $return_item['day'], $return_item['type'])){
					$return_item['id'] = $occurances['Time']['id'];
					$return_item['occurances'] = $occurances['Time']['occurances'] + 1;
				}
				
				$return[] = $return_item;
			}
		}
		
		return (!empty($return)) ? $return : false;
	}
	
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
					'Time' => $this->__groupByHoursAndDay($times),
				);
			}
		}
		
		return $station;
	}
	
	public function line($line = null){
		$this->Line->id = $line;
		if(!$this->Line->exists()){
			throw new NotFoundException(__('Linie invalida'));
		}
		
		$directions = $this->Line->directions($line);
		foreach($directions as &$direction){
			foreach($direction as &$station){
				$times = $this->find('all', array('conditions' => array('Time.station_id' => $station['Station']['id'], 'Time.line_id' => $line), 'order' => 'Time.time ASC'));
				$station['Time'] = $this->__groupByHoursAndDay($times);	
			}
		}
		return $directions;
	}
	
	private function __groupByHoursAndDay($times){
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
		
	public function getTime($stationLineId = null){
		if (!$this->stationLine = $this->Line->StationLine->find('first', array(
			'conditions' => array('StationLine.id' => $stationLineId)
		))) {
			return false;
		}
		
		if (!$this->_fetchTime()) {
			$this->_logFileNotOpened();
			return false;
		}
		
		if (!$this->_parseTime()) {
			$this->_logFileChanged();
			return false;
		}
		
		$this->_validateTime();
		
		$this->_processTime();
		
		if (!$this->_saveTime()) {
			$this->_logTimeNotSaved();
			return false;
		}
		
		$this->_logTimeSaved();
		return true;
	}
	
	protected function _fetchTime(){
		return $this->_html = file_get_contents(sprintf(
			Configure::read('Ratt.url'), 
			$this->stationLine['Line']['id_ratt'], 
			$this->stationLine['Station']['id_ratt']
		));
	}
	
	protected function _parseTime(){
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
			($font[1] == '>>' || strtotime($font[1]) || $font[1] == '**:**') && 
			$font[2] == 'Sosire2:' && 
			(strtotime($font[3]) || $font[3] == '**:**')
		){
			$this->_time = array(
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
	
	protected function _validateTime(){
		//unset null times (e.g.: **:**)
		foreach($this->_time as $i => &$time){
			if(is_null($time['time'])){
				unset($this->_time[$i]);	
			}
		}
		
		//if times in graph mode are very close (~1-2 min), keep only the first one
		if(
			isset($this->_time[0]) &&
			isset($this->_time[1]) &&
			$this->_time[0]['type'] == 'G' && 
			$this->_time[1]['type'] == 'G' && 
			$this->_time[1]['time'] - $this->_time[0]['time'] < 3 * 60
		){
			unset($this->_time[1]);	
		}
		
		//if first time is over 30mins than current time, don't save the times
		if(
			isset($this->_time[0]) &&
			$this->_time[0]['time'] - time() > 30 * 60
		){
			unset($this->_time[0]);
			if (isset($this->_time[1])) {
				unset($this->_time[1]);
			}
		}
	}
	
	protected function _processTime(){
		if (!empty($this->_time)) {
			foreach($this->_time as &$time){
				$time['station_id'] = $this->stationLine['Station']['id'];
				$time['line_id'] = $this->stationLine['Line']['id'];
				$time['day'] = $this->_dayType($time['time']);
				$time['time'] = $this->_formatTime($time['time']);
				if($occurances = $this->_timeOccurances($time['time'], $time['day'], $time['type'])){
					$time['id'] = $occurances['Time']['id'];
					$time['occurances'] = $occurances['Time']['occurances'] + 1;
				}
			}
			unset($time);
		}
	}
	
	protected function _saveTime(){
		$this->time = $this->_time;
		
		if(count($this->_time) > 1){
			return $this->saveMany($this->_time);	
		} elseif(count($this->_time) == 1) {
			return $this->save(array('Time' => array_shift(array_values($this->_time))));
		} else {
			return true;
		}
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
		$message = 'Timpii nu au putut fi salvati pentru statia '.$this->stationLine['Station']['name_direction'].', linia '.$this->stationLine['Line']['name'].' (<code>$stationLineId = '.$this->stationLine['StationLine']['id'].'</code>)';
		$this->_logWrite($type, $message);
	}
	
	protected function _logTimeSaved(){
		$type = 'time-info';
		if(count($this->_time) > 0){
			$message = 'S-au salvat urmatorii timpi pentru statia '.$this->stationLine['Station']['name_direction'].', linia '.$this->stationLine['Line']['name'].': ';
			foreach($this->_time as $time){
				$message .= '<code>'.$time['time'].' '.$time['day'].'</code><code>'.$time['type'].'</code>';	
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
	
	protected function _logWrite($type, $message){
		CakeLog::write($type, $message);
	}
}
