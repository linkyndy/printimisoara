<?php
App::uses('AppController', 'Controller');

class LinesController extends AppController {

	public $uses = array('Line', 'Station', 'StationLine', 'Suggestion');
	
	public $paginate = array(
		'order' => array(
			'Line.name' => 'ASC'
		)
	);

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
		$this->Line->recursive = 0;
		$this->set('lines', $this->paginate());
		$this->set('line_list', $this->Line->find('list'));
	}

	public function admin_search() {
		if($id = $this->Line->field('id', array('name' => $this->request->data['Line']['search']))){
			$this->redirect(array('action' => 'view', $id));	
		}
		$this->Session->setFlash('Linia nu a fost gasita', 'error');
		$this->redirect(array('action' => 'index'));
	}

	public function admin_view($id = null) {
		$this->Line->id = $id;
		if (!$this->Line->exists()) {
			throw new NotFoundException(__('Linie invalida'));
		}
		$this->set('line', $this->Line->read(null, $id));
		$this->set('directions', $this->Line->directions($id));
	}

	public function admin_map($id = null) {
		$this->Line->id = $id;
		if (!$this->Line->exists()) {
			throw new NotFoundException(__('Linie invalida'));
		}
		$this->set('line', $this->Line->find('first', array(
			'conditions' => array('Line.id' => $id),
			'fields' => array('Line.id'),
			'contain' => array(
				'StationLine' => array(
					'fields' => array('StationLine.id', 'StationLine.station_id'),
					'Station' => array(
						'fields' => array('Station.id', 'Station.name_direction', 'Station.lat', 'Station.lng'),
						'FromStationConnection',
						'ToStationConnection',
						'FromStationPoint',
						'ToStationPoint'
					)
				)
			)
		)));
		$this->set('_serialize', 'line');
	}

	public function admin_add() {
		if ($this->request->is('post')) {
			$this->Line->create();
			if ($this->Line->save($this->request->data)) {
				$this->Session->setFlash(__('Linia a fost adaugata!'), 'success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('Linia nu a putut fi adaugata.'), 'error');
			}
		}
	}

	public function admin_edit($id = null) {
		$this->Line->id = $id;
		if (!$this->Line->exists()) {
			throw new NotFoundException(__('Invalid line'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Line->save($this->request->data)) {
				$this->Session->setFlash(__('Linia a fost salvata!'), 'success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('Linia nu a putut fi salvata.'), 'error');
			}
		} else {
			$this->request->data = $this->Line->read(null, $id);
		}
	}

	public function admin_delete($id = null) {
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		$this->Line->id = $id;
		if (!$this->Line->exists()) {
			throw new NotFoundException(__('Invalid line'));
		}
		if ($this->Line->delete()) {
			$this->Session->setFlash(__('Linia a fost stearsa!'), 'success');
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Linia nu a putut fi stearsa.'), 'error');
		$this->redirect(array('action' => 'index'));
	}

	public function admin_validate_nodes($id = null) {
		$this->Line->id = $id;
		if (!$this->Line->exists()) {
			throw new NotFoundException(__('Invalid line'));
		}
		
		$stations = $this->StationLine->find('list', array(
			'conditions' => array('StationLine.line_id' => $id),
			'fields' => array('StationLine.station_id')
		));
		$stationGroups = $this->Station->find('list', array(
			'conditions' => array('Station.id' => $stations),
			'fields' => array('Station.station_group_id'),
			'group' => array('Station.station_group_id')
		));
		
		foreach($stationGroups as $stationGroupId){
			if(!$this->Station->validateNodes($stationGroupId)){
				$this->Session->setFlash(__('Nodurile nu au putut fi verificate.'), 'error');
				$this->redirect(array('action' => 'view', $id));
			}
		}
		
		$this->Session->setFlash(__('Nodurile au fost verificate!'), 'success');
		$this->redirect(array('action' => 'view', $id));
	}
	
/**
 * Superuser
 */
 
 	public function superuser_index() {
		$this->Line->recursive = 0;
		$this->set('lines', $this->paginate());
		$this->set('line_list', $this->Line->find('list'));
	}

	public function superuser_search() {
		if($id = $this->Line->field('id', array('name' => $this->request->data['Line']['search']))){
			$this->redirect(array('action' => 'view', $id));	
		}
		$this->Session->setFlash('Linia nu a fost gasita', 'error');
		$this->redirect(array('action' => 'index'));
	}

	public function superuser_view($id = null) {
		$this->Line->id = $id;
		if (!$this->Line->exists()) {
			throw new NotFoundException(__('Linie invalida'));
		}
		$this->set('line', $this->Line->read(null, $id));
		$this->set('directions', $this->Line->directions($id));
	}

	public function superuser_map($id = null) {
		$this->Line->id = $id;
		if (!$this->Line->exists()) {
			throw new NotFoundException(__('Linie invalida'));
		}
		$this->set('line', $this->Line->find('first', array(
			'conditions' => array('Line.id' => $id),
			'fields' => array('Line.id'),
			'contain' => array(
				'StationLine' => array(
					'fields' => array('StationLine.id', 'StationLine.station_id'),
					'Station' => array(
						'fields' => array('Station.id', 'Station.name_direction', 'Station.lat', 'Station.lng'),
						'FromStationConnection',
						'ToStationConnection',
						'FromStationPoint',
						'ToStationPoint'
					)
				)
			)
		)));
		$this->set('_serialize', 'line');
	}

	public function superuser_edit($id = null) {
		$this->Line->id = $id;
		if (!$this->Line->exists()) {
			throw new NotFoundException(__('Invalid line'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			$this->request->data = $this->request->data['Line'];
			$this->request->data['line_id'] = $this->request->data['id'];
			$this->request->data['user_id'] = $this->Auth->user('id');
			unset($this->request->data['id']);
			
			if ($this->Suggestion->save($this->request->data)) {
				$this->Session->setFlash(__('Sugestia a fost salvata!'), 'success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('Sugestia nu a putut fi salvata.'), 'error');
			}
		} else {
			$this->request->data = $this->Line->read(null, $id);
		}
	}
}