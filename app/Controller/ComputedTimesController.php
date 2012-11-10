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
		$this->Time->recursive = 0;
		$this->set('times', $this->paginate());
		$this->set('station_line_list', $this->StationLine->formatStationLines());
	}
	/*public function admin_delete($id = null) {
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		$this->Time->id = $id;
		if (!$this->Time->exists()) {
			throw new NotFoundException(__('Invalid time'));
		}
		if ($this->Time->delete()) {
			$this->Session->setFlash(__('Time deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Time was not deleted'));
		$this->redirect(array('action' => 'index'));
	}*/
}
