<?php
App::uses('AppController', 'Controller');

class StationPointsController extends AppController {
	
	public $uses = array('StationPoint', 'Station');

	public function admin_index($type = null) {
		$this->StationPoint->recursive = 0;
		$this->set('stationPoints', $this->paginate());
	}
	
/**
 * admin_view method
 *
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		$this->StationPoint->id = $id;
		if (!$this->StationPoint->exists()) {
			throw new NotFoundException(__('Puncte invalide'));
		}
		$this->StationPoint->recursive = 0;
		$this->set('stationPoint', $this->StationPoint->read(null, $id));
	}
	
/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->StationPoint->create();
			
			unset($this->request->data['StationPoint']['from']);
			unset($this->request->data['StationPoint']['to']);
			unset($this->request->data['StationPoint']['from_station_lat']);
			unset($this->request->data['StationPoint']['from_station_lng']);
			unset($this->request->data['StationPoint']['to_station_lat']);
			unset($this->request->data['StationPoint']['to_station_lng']);
			
			if ($this->StationPoint->save($this->request->data)) {
				$this->Session->setFlash(__('Punctele au fost adaugate!'), 'success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('Punctele nu a putut fi adaugate.'), 'error');
			}
		}
		$stations = $this->Station->find('list', array('fields' => array('Station.name_direction')));
		$this->set(compact('stations'));
	}

/**
 * admin_edit method
 *
 * @param string $id
 * @return void
 */
	public function admin_edit($id = null) {
		$this->StationPoint->id = $id;
		if (!$this->StationPoint->exists()) {
			throw new NotFoundException(__('Puncte invalide'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->StationPoint->save($this->request->data)) {
				$this->Session->setFlash(__('Punctele au fost salvate!'), 'success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('Punctele nu au putut fi salvate.'), 'error');
			}
		} else {
			$this->request->data = $this->StationPoint->read(null, $id);
		}
	}
	
/**
 * admin_stations method
 *
 * @return void
 */
	public function admin_stations() {
		$this->Station->recursive = 0;
		$this->set('stations', array('from' => $this->Station->find('first', array('conditions' => array('Station.name_direction' => $this->request->data['from']))), 'to' => $this->Station->find('first', array('conditions' => array('Station.name_direction' => $this->request->data['to'])))));
		$this->set('_serialize', 'stations');
	}

/**
 * admin_delete method
 *
 * @param string $id
 * @return void
 */
	public function admin_delete($id = null) {
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		$this->StationPoint->id = $id;
		if (!$this->StationPoint->exists()) {
			throw new NotFoundException(__('Puncte invalide'));
		}
		if ($this->StationPoint->delete()) {
			$this->Session->setFlash(__('Punctele au fost sterse!'), 'success');
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Punctele nu a putut fi sterse.'), 'error');
		$this->redirect(array('action' => 'index'));
	}
}


