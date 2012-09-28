<?php
App::uses('AppController', 'Controller');
/**
 * Routes Controller
 *
 * @property Station $Station
 */
class RoutesController extends AppController {


/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->Station->recursive = 0;
		$this->set('stations', $this->paginate());
	}

/**
 * view method
 *
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$this->Station->id = $id;
		if (!$this->Station->exists()) {
			throw new NotFoundException(__('Invalid station'));
		}
		$this->set('station', $this->Station->read(null, $id));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Station->create();
			if ($this->Station->save($this->request->data)) {
				$this->Session->setFlash(__('The station has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The station could not be saved. Please, try again.'));
			}
		}
		$stationGroups = $this->Station->StationGroup->find('list');
		$this->set(compact('stationGroups'));
	}

/**
 * edit method
 *
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		$this->Station->id = $id;
		if (!$this->Station->exists()) {
			throw new NotFoundException(__('Invalid station'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Station->save($this->request->data)) {
				$this->Session->setFlash(__('The station has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The station could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->Station->read(null, $id);
		}
		$stationGroups = $this->Station->StationGroup->find('list');
		$this->set(compact('stationGroups'));
	}

/**
 * delete method
 *
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		$this->Station->id = $id;
		if (!$this->Station->exists()) {
			throw new NotFoundException(__('Invalid station'));
		}
		if ($this->Station->delete()) {
			$this->Session->setFlash(__('Station deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Station was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
/**
 * admin_index method
 *
 * @return void
 */
	public function admin_index() {
		$this->Station->recursive = 0;
		$this->set('stations', $this->paginate());
		$this->set('station_list', $this->Station->find('list', array('fields' => array('Station.name_direction'))));
	}
	
/**
 * admin_search method
 *
 * @return void
 */
	public function admin_search() {
		if($id = $this->Station->field('id', array('name_direction' => $this->request->data['Station']['search']))){
			$this->redirect(array('action' => 'view', $id));	
		}
		$this->Session->setFlash('Statia nu a fost gasita', 'error');
		$this->redirect(array('action' => 'index'));
	}


/**
 * admin_view method
 *
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		$this->Station->id = $id;
		if (!$this->Station->exists()) {
			throw new NotFoundException(__('Statie invalida'));
		}
		$this->Station->recursive = 2;
		$this->set('station', $this->Station->read(null, $id));
	}

/**
 * admin_add method
 *
 * @return void
 */
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

/**
 * admin_edit method
 *
 * @param string $id
 * @return void
 */
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
}
