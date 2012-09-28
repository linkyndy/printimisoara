<?php
App::uses('AppController', 'Controller');

class FollowingStationLinesController extends AppController {
	
	public $uses = array('FollowingStationLine', 'Line');

/**
 * Everyone
 */

/**
 * Logged users
 */
 
/**
 * Admin
 */
 
	public function admin_compute() {
		$time = microtime(true);
		if(
			$this->FollowingStationLine->compute($this->Line->find('list', array(
				'fields' => array('Line.id')
			)))
		){
			$this->Session->setFlash('Cache-ul statiilor urmatoare a fost refacut in '.round((microtime(true) - $time), 5).' secunde!', 'success');
			$this->redirect(array('controller' => 'stations', 'action' => 'index'));
		}
		$this->Session->setFlash('Cache-ul statiilor urmatoare nu a putut fi refacut ('.round((microtime(true) - $time), 5).' secunde).', 'success');
		$this->redirect(array('controller' => 'stations', 'action' => 'index'));
	}
	
/**
 * Superuser
 */

}
