<?php
App::uses('AppController', 'Controller');

class StationGroupsController extends AppController {

	public $uses = array('StationGroup', 'Suggestion');
/**
 * Everyone
 */
 
/**
 * Logged users
 */
 
/**
 * Admin
 */
	public function admin_index() {
		$this->StationGroup->recursive = 0;
		$this->set('stationGroups', $this->paginate());
		$this->set('station_group_list', $this->StationGroup->find('list'));
	}
	
	public function admin_search() {
		if($id = $this->StationGroup->field('id', array('name' => $this->request->data['StationGroup']['search']))){
			$this->redirect(array('action' => 'view', $id));	
		}
		$this->Session->setFlash('Grupul de statii nu a fost gasit', 'error');
		$this->redirect(array('action' => 'index'));
	}

	public function admin_view($id = null) {
		$this->StationGroup->id = $id;
		if (!$this->StationGroup->exists()) {
			throw new NotFoundException(__('Grup de statii invalid'));
		}
		$this->set('stationGroup', $this->StationGroup->find('first', array(
			'conditions' => array(
				'StationGroup.id' => $id
			),
			'contain' => array(
				'Station' => array(
					'StationLine' => array(
						'fields' => array('StationLine.id', 'StationLine.station_id', 'StationLine.line_id'),
						'Line' => array(
							'fields' => array('Line.id', 'Line.name', 'Line.colour'),
						),
					),
				),
			),
		)));
	}

	public function admin_map($id = null) {
		$this->StationGroup->id = $id;
		if (!$this->StationGroup->exists()) {
			throw new NotFoundException(__('Grup de statii invalid'));
		}
		$this->set('station_group', $this->StationGroup->find('all', array(
			'fields' => array('StationGroup.id'),
			'conditions' => array('StationGroup.id' => $id),
			'contain' => array(
				'Station' => array(
					'fields' => array('Station.id', 'Station.name_direction', 'Station.lat', 'Station.lng')
				)
			)
		)));
		$this->set('_serialize', 'station_group');
	}

	public function admin_add() {
		if ($this->request->is('post')) {
			$this->StationGroup->create();
			if ($this->StationGroup->save($this->request->data)) {
				$this->Session->setFlash(__('Grupul de statii a fost adaugat!'), 'success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('Grupul de statii nu a putut fi adaugat.'), 'error');
			}
		}
	}

	public function admin_edit($id = null) {
		$this->StationGroup->id = $id;
		if (!$this->StationGroup->exists()) {
			throw new NotFoundException(__('Invalid station group'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->StationGroup->save($this->request->data)) {
				$this->Session->setFlash(__('Grupul de statii a fost salvat!'), 'success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('Grupul de statii nu a putut fi salvat.'), 'error');
			}
		} else {
			$this->request->data = $this->StationGroup->read(null, $id);
		}
	}

	public function admin_delete($id = null) {
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		$this->StationGroup->id = $id;
		if (!$this->StationGroup->exists()) {
			throw new NotFoundException(__('Grup de statii invalid'));
		}
		if ($this->StationGroup->delete()) {
			$this->Session->setFlash(__('Station group deleted'), 'success');
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Station group was not deleted'), 'error');
		$this->redirect(array('action' => 'index'));
	}
	
/**
 * Superuser
 */
 
 	public function superuser_index() {
		$this->StationGroup->recursive = 0;
		$this->set('stationGroups', $this->paginate());
		$this->set('station_group_list', $this->StationGroup->find('list'));
	}
	
	public function superuser_search() {
		if($id = $this->StationGroup->field('id', array('name' => $this->request->data['StationGroup']['search']))){
			$this->redirect(array('action' => 'view', $id));	
		}
		$this->Session->setFlash('Grupul de statii nu a fost gasit', 'error');
		$this->redirect(array('action' => 'index'));
	}

	public function superuser_view($id = null) {
		$this->StationGroup->id = $id;
		if (!$this->StationGroup->exists()) {
			throw new NotFoundException(__('Grup de statii invalid'));
		}
		$this->set('stationGroup', $this->StationGroup->read(null, $id));
	}

	public function superuser_map($id = null) {
		$this->StationGroup->id = $id;
		if (!$this->StationGroup->exists()) {
			throw new NotFoundException(__('Grup de statii invalid'));
		}
		$this->set('station_group', $this->StationGroup->find('all', array(
			'fields' => array('StationGroup.id'),
			'conditions' => array('StationGroup.id' => $id),
			'contain' => array(
				'Station' => array(
					'fields' => array('Station.id', 'Station.name_direction', 'Station.lat', 'Station.lng')
				)
			)
		)));
		$this->set('_serialize', 'station_group');
	}

	public function superuser_edit($id = null) {
		$this->StationGroup->id = $id;
		if (!$this->StationGroup->exists()) {
			throw new NotFoundException(__('Grup de statii invalid'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			$this->request->data = $this->request->data['StationGroup'];
			$this->request->data['station_group_id'] = $this->request->data['id'];
			$this->request->data['user_id'] = $this->Auth->user('id');
			unset($this->request->data['id']);
			
			if ($this->Suggestion->save($this->request->data)) {
				$this->Session->setFlash(__('Sugestia a fost salvata!'), 'success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('Sugestia nu a putut fi salvata.'), 'error');
			}
		} else {
			$this->request->data = $this->StationGroup->read(null, $id);
		}
	}
}
