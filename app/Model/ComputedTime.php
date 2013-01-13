<?php

App::uses('AppModel', 'Model');

class ComputedTime extends AppModel {

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
		'log' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Trebuie sa existe un log pentru timp!',
				'last' => true,
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
	
	/*
	 * Save detailed time
	 */
	public function saveTime(){
		$log = '<table class="table table-bordered">';
		
		$log .= '<tr>';
		$log .= '<td>Fetch</td>';
		if (!empty($this->Station->Time->time['fetch']['times'])) {
			$log .= '<td><span class="label">' . $this->Station->Time->time['fetch']['time'] . '</span></td>';
			$log .= '<td>';
			foreach ($this->Station->Time->time['fetch']['times'] as $time) {
				$log .= '<span class="label label-' . $time['type'] . '">' . CakeTime::format('H:i', strtotime($time['time'])) . '</span><span class="label label-' . $time['day'] . '">' . $time['day'] . '</span> ';
			}
			$log .= '</td>';
		} else {
			$log .= '<td colspan="2">N/A</td>';
		}
		$log .= '</tr>';
		
		/* @TODO: implement followUp
		$log .= '<tr><td>Follow-up</td><td>';
		if (!empty($this->followUp)) {
			$log .= '<code>' . $this->followUp['time'] . '</code>';
		} else {
			$log .= 'N/A';
		}
		$log .= '</td><td>';
		if (!empty($this->followUpTimes)) {
			// [0] because there is only one time saved for each station (M-type time)
			// in $this->_recursive from those fetched with $this->_fetchRecursive()
			debug($this->followUp);debug($this->followUpTimes);exit;
			$log .= ((count($this->followUpTimes) == 1) ? 'S-a mers 1 statie in sus' : 'S-au mers ' . count($this->followUpTimes) . ' statii in sus') . ', adica ' . ((strtotime($this->followUp['time']) - strtotime($this->followUpTimes[count($this->followUpTimes) - 1][0]['time'])) / 60) . ' minut(e)';
			foreach ($this->followUpTimes as $time) {
				$log .= 'Statia ' . $this->Station->field('name_direction', array('id' => $time[0]['station_id'])) . ', linia ' . $this->Line->field('name', array('id')) . '<code>'.$time[0]['time'].' '.$time[0]['day'].'</code><code>'.$time[0]['type'].'</code>.';
			}
		} else {
			$log .= 'N/A';
		}
		$log .= '</td></tr>';
		*/
		
		$log .= '<tr>';
		$log .= '<td>Database</td>';
		if (!empty($this->Station->Time->time['database']['times'])) {
			$log .= '<td><span class="label">' . $this->Station->Time->time['database']['time'] . '</span></td>';
			$log .= '<td>';
			foreach ($this->Station->Time->time['database']['times'] as $time) {
				$log .= '<span class="label label-' . $time['type'] . '">' . CakeTime::format('H:i', strtotime($time['time'])) . '</span><span class="label label-' . $time['day'] . '">' . $time['day'] . '</span> ';
			}
			$log .= '</td>';
		} else {
			$log .= '<td colspan="2">N/A</td>';
		}
		$log .= '</tr>';
		
		$log .= '</table>';
		
		$log .= 'Timpul final este <code>' . $this->Station->Time->finalTime . '</code> si s-a obtinut pe baza formulei: 
			<code>(' . ((!empty($this->Station->Time->time['fetch']['times'])) ? $this->Station->Time->time['fetch']['time'] . ' * ' . $this->Station->Time->time['fetch']['weight'] : 0) . /*' + ' . ((!empty($this->followUp)) ? $this->followUp['time'] . ' * ' . $this->followUp['weight'] : 0) . */' + ' . ((!empty($this->Station->Time->time['database']['times'])) ? $this->Station->Time->time['database']['time'] . ' * ' . $this->Station->Time->time['database']['weight'] : 0) . ') / (' . (!empty($this->Station->Time->time['fetch']['times']) ? $this->Station->Time->time['fetch']['weight'] : 0) . /*' + ' . (!empty($this->followUp) ? $this->followUp['weight'] : 0) . */' + ' . (!empty($this->Station->Time->time['database']['times']) ? $this->Station->Time->time['database']['weight'] : 0) . ')</code>'
		;
		
		return $this->save(array(
			'ComputedTime' => array(
				'station_id' => $this->Station->Time->stationLine['Station']['id'],
				'line_id' => $this->Line->Time->stationLine['Line']['id'],
				'time' => $this->Station->Time->finalTime,
				'log' => $log,
			),
		));
	}
}
