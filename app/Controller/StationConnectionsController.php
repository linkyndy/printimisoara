<?php
App::uses('AppController', 'Controller');

class StationConnectionsController extends AppController {
	
	public $uses = array('StationConnection', 'Station');

	public function admin_index($type = null) {
		$this->StationConnection->recursive = 0;
		$this->set('stationConnections', $this->paginate());
	}
	
/**
 * admin_view method
 *
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		$this->StationConnection->id = $id;
		if (!$this->StationConnection->exists()) {
			throw new NotFoundException(__('Conexiune invalida'));
		}
		$this->StationConnection->recursive = 0;
		$this->set('stationConnection', $this->StationConnection->read(null, $id));
	}
	
/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->StationConnection->create();
			
			unset($this->request->data['StationConnection']['from']);
			unset($this->request->data['StationConnection']['to']);
			
			if ($this->StationConnection->save($this->request->data)) {
				$this->Session->setFlash(__('Conexiunea a fost adaugata!'), 'success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('Conexiunea nu a putut fi adaugata.'), 'error');
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
		$this->StationConnection->id = $id;
		if (!$this->StationConnection->exists()) {
			throw new NotFoundException(__('Conexiune invalida'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->StationConnection->save($this->request->data)) {
				$this->Session->setFlash(__('Conexiunea a fost salvata!'), 'success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('Conexiunea nu a putut fi salvata.'), 'error');
			}
		} else {
			$this->request->data = $this->StationConnection->read(null, $id);
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
		$this->StationConnection->id = $id;
		if (!$this->StationConnection->exists()) {
			throw new NotFoundException(__('Conexiune invalida'));
		}
		if ($this->StationConnection->delete()) {
			$this->Session->setFlash(__('Conexiunea a fost stearsa!'), 'success');
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Conexiunea nu a putut fi stearsa.'), 'error');
		$this->redirect(array('action' => 'index'));
	}
}


