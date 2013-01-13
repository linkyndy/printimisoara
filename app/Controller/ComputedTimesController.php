<?php
App::uses('AppController', 'Controller');

class ComputedTimesController extends AppController {
	
	public $uses = array('ComputedTime', 'Time', 'Station', 'Line', 'StationLine');
	
	public $paginate = array(
		'order' => array(
			'ComputedTime.modified' => 'DESC'
		),
		'limit' => 50,
	);

/**
 * Everybody
 */
 
/**
 * Logged users
 */
 
/**
 * Admin
 */

	public function admin_index() {
		$this->set('times', $this->paginate());
	}
}
