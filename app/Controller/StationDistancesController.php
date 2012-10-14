<?php
App::uses('AppController', 'Controller');

class StationDistancesController extends AppController {
	
	public $uses = array('StationDistance', 'Station');

	public function admin_index($type = null) {
		$this->StationDistance->recursive = 0;
		$this->set('stationDistances', $this->paginate());
	}
	
/**
 * admin_view method
 *
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		$this->StationDistance->id = $id;
		if (!$this->StationDistance->exists()) {
			throw new NotFoundException(__('Distanta invalida'));
		}
		$this->StationDistance->recursive = 0;
		$this->set('stationDistance', $this->StationDistance->read(null, $id));
	}
	
/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->StationDistance->create();
			
			$this->request->data['StationDistance']['from_station_id'] = $this->Station->field('id', array(
				'Station.name_direction' => $this->request->data['StationDistance']['from']
			));
			$this->request->data['StationDistance']['to_station_id'] = $this->Station->field('id', array(
				'Station.name_direction' => $this->request->data['StationDistance']['to']
			));
			
			if ($this->StationDistance->save($this->request->data)) {
				$this->Session->setFlash(__('Distanta a fost adaugata!'), 'success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('Distanta nu a putut fi adaugata.'), 'error');
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
		$this->StationDistance->id = $id;
		if (!$this->StationDistance->exists()) {
			throw new NotFoundException(__('Distanta invalida'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->StationDistance->save($this->request->data)) {
				$this->Session->setFlash(__('Distanta a fost salvata!'), 'success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('Distanta nu a putut fi salvata.'), 'error');
			}
		} else {
			$this->request->data = $this->StationDistance->read(null, $id);
		}
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
		$this->StationDistance->id = $id;
		if (!$this->StationDistance->exists()) {
			throw new NotFoundException(__('Puncte invalide'));
		}
		if ($this->StationDistance->delete()) {
			$this->Session->setFlash(__('Distanta a fost stearsa!'), 'success');
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Distanta nu a putut fi stearsa.'), 'error');
		$this->redirect(array('action' => 'index'));
	}
}


