<?php
App::uses('AppController', 'Controller');

class StationsController extends AppController {
	
	public $uses = array('Station', 'StationLine', 'Suggestion');
	
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
		$this->Station->recursive = 0;
		$this->set('stations', $this->paginate());
		$this->set('station_list', $this->Station->find('list', array('fields' => array('Station.name_direction'))));
	}
	
	public function admin_nodes() {
		$this->Station->recursive = 0;
		$this->set('stations', $this->paginate('Station', array('Station.node' => 1)));
		$this->set('station_list', $this->Station->find('list', array('fields' => array('Station.name_direction'), 'conditions' => array('Station.node' => 1))));
	}
	
	/**
	 * Get a list of stations that are near
	 * the provided coordinates
	 *
	 * This method should only be called via ajax
	 */
	public function admin_near_stations($lat, $lng){
		if ($this->request->is('ajax')) {
			$stations = $this->Station->nearStations(array(
				'lat' => $lat,
				'lng' => $lng,
			));
			$stationLines = $this->StationLine->find('all', array(
				'conditions' => array(
					'StationLine.station_id' => $stations,
				),
				'fields' => array('StationLine.id', 'StationLine.station_id', 'StationLine.line_id'),
				'contain' => array(
					'Station' => array(
						'fields' => array('Station.id', 'Station.name', 'Station.direction')
					),
					'Line' => array(
						'fields' => array('Line.id', 'Line.name', 'Line.colour'),
					),
				),
			));
			$this->set('stationLines', $stationLines);
			$this->set('_serialize', array('stationLines'));
		}
	}

	public function admin_search() {
		if($id = $this->Station->field('id', array('name_direction' => $this->request->data['Station']['search']))){
			$this->redirect(array('action' => 'view', $id));	
		}
		$this->Session->setFlash('Statia nu a fost gasita', 'error');
		$this->redirect(array('action' => 'index'));
	}

	public function admin_view($id = null) {
		$this->Station->id = $id;
		if (!$this->Station->exists()) {
			throw new NotFoundException(__('Statie invalida'));
		}
		$this->Station->recursive = 2;
		$this->set('station', $this->Station->read(null, $id));
	}

	public function admin_add() {
		if ($this->request->is('post')) {
			$this->Station->create();
			
			//if station group is found, retrieve its id from db. if not, add it to db
			if($station_group_id = $this->Station->StationGroup->field('id', array('name' => $this->request->data['StationGroup']['name']))){
				$this->request->data['StationGroup']['id'] = $station_group_id;	
			}
			
			if ($this->Station->saveAll($this->request->data)) {
				$this->Session->setFlash(__('Statia a fost adaugata!'), 'success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('Statia nu a putut fi adaugata.'), 'error');
			}
		}
		$stationGroups = $this->Station->StationGroup->find('list');
		$this->set(compact('stationGroups'));
	}

	public function admin_edit($id = null) {
		$this->Station->id = $id;
		if (!$this->Station->exists()) {
			throw new NotFoundException(__('Statie invalida'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			
			//if station group is found, retrieve its id from db. if not, add it to db
			if($station_group_id = $this->Station->StationGroup->field('id', array('name' => $this->request->data['StationGroup']['name']))){
				$this->request->data['StationGroup']['id'] = $station_group_id;	
			}
			
			if ($this->Station->saveAll($this->request->data)) {
				$this->Session->setFlash(__('Statia a fost salvata!'), 'success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('Statia nu a putut fi salvata.'), 'error');
			}
		} else {
			$this->request->data = $this->Station->read(null, $id);
		}
		$stationGroups = $this->Station->StationGroup->find('list');
		$this->set(compact('stationGroups'));
	}

	public function admin_delete($id = null) {
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		$this->Station->id = $id;
		if (!$this->Station->exists()) {
			throw new NotFoundException(__('Statie invalida'));
		}
		if ($this->Station->delete()) {
			$this->Session->setFlash(__('Statia a fost stearsa!'), 'success');
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Statia nu a putut fi stearsa.'), 'error');
		$this->redirect(array('action' => 'index'));
	}
	
/**
 * Superuser
 */

	public function superuser_index() {
		$this->Station->recursive = 0;
		$this->set('stations', $this->paginate());
		$this->set('station_list', $this->Station->find('list', array('fields' => array('Station.name_direction'))));
	}
	
	public function superuser_nodes() {
		$this->Station->recursive = 0;
		$this->set('stations', $this->paginate('Station', array('Station.node' => 1)));
		$this->set('station_list', $this->Station->find('list', array('fields' => array('Station.name_direction'), 'conditions' => array('Station.node' => 1))));
	}

	public function superuser_search() {
		if($id = $this->Station->field('id', array('name_direction' => $this->request->data['Station']['search']))){
			$this->redirect(array('action' => 'view', $id));	
		}
		$this->Session->setFlash('Statia nu a fost gasita', 'error');
		$this->redirect(array('action' => 'index'));
	}

	public function superuser_view($id = null) {
		$this->Station->id = $id;
		if (!$this->Station->exists()) {
			throw new NotFoundException(__('Statie invalida'));
		}
		$this->Station->recursive = 2;
		$this->set('station', $this->Station->read(null, $id));
	}
	
	public function superuser_edit($id = null) {
		$this->Station->id = $id;
		if (!$this->Station->exists()) {
			throw new NotFoundException(__('Statie invalida'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			$this->request->data = $this->request->data['Station'];
			$this->request->data['station_id'] = $this->request->data['id'];
			$this->request->data['user_id'] = $this->Auth->user('id');
			unset($this->request->data['id']);
			
			if ($this->Suggestion->save($this->request->data)) {
				$this->Session->setFlash(__('Sugestia a fost salvata!'), 'success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('Sugestia nu a putut fi salvata.'), 'error');
			}
		} else {
			$this->request->data = $this->Station->read(null, $id);
		}
	}

}
