<?php
App::uses('AppController', 'Controller');
/**
 * StationLines Controller
 *
 * @property StationLine $StationLine
 */
class StationLinesController extends AppController {

	public $uses = array('StationLine', 'Station', 'Line');

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->StationLine->recursive = 0;
		$this->set('stationLines', $this->paginate());
	}

/**
 * view method
 *
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$this->StationLine->id = $id;
		if (!$this->StationLine->exists()) {
			throw new NotFoundException(__('Invalid station line'));
		}
		$this->set('stationLine', $this->StationLine->read(null, $id));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->StationLine->create();
			if ($this->StationLine->save($this->request->data)) {
				$this->Session->setFlash(__('The station line has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The station line could not be saved. Please, try again.'));
			}
		}
		$stations = $this->StationLine->Station->find('list');
		$lines = $this->StationLine->Line->find('list');
		$this->set(compact('stations', 'lines'));
	}

/**
 * edit method
 *
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		$this->StationLine->id = $id;
		if (!$this->StationLine->exists()) {
			throw new NotFoundException(__('Invalid station line'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->StationLine->save($this->request->data)) {
				$this->Session->setFlash(__('The station line has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The station line could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->StationLine->read(null, $id);
		}
		$stations = $this->StationLine->Station->find('list');
		$lines = $this->StationLine->Line->find('list');
		$this->set(compact('stations', 'lines'));
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
		$this->StationLine->id = $id;
		if (!$this->StationLine->exists()) {
			throw new NotFoundException(__('Invalid station line'));
		}
		if ($this->StationLine->delete()) {
			$this->Session->setFlash(__('Station line deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Station line was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
/**
 * admin_index method
 *
 * @return void
 */
	public function admin_index() {
		$this->StationLine->recursive = 0;
		$this->set('stationLines', $this->paginate());
	}

/**
 * admin_view method
 *
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		$this->Line->id = $id;
		if (!$this->Line->exists()) {
			throw new NotFoundException(__('Linie invalida'));
		}
		$this->set('line', $this->Line->read(null, $id));
		$this->set('directions', $this->Line->directions($id));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add($id = null) {
		$this->Line->id = $id;
		if (!$this->Line->exists()) {
			throw new NotFoundException(__('Linie invalida'));
		}
		if ($this->request->is('post')) {
			$this->StationLine->create();
			
			$data['StationLine'] = array();
			$duplicates = array();
			foreach($this->request->data['Station'] as $station){
				$station_line = array(
					'station_id' => $this->Station->field('id', array('name_direction' => $station['name_direction'])),
					'line_id' => $id,
					'order' => $station['order'],
					'end' => $station['end'],
				);
				if($station_line_id = $this->StationLine->field('id', array('station_id' => $station_line['station_id'], 'line_id' => $id))){
					$station_line['id'] = $station_line_id;
				}
				$data['StationLine'][] = $station_line;
				
				$duplicates[$station_line['station_id']] = array_key_exists($station_line['station_id'], $duplicates) ? $duplicates[$station_line['station_id']] + 1 : 1;
			}
			foreach($duplicates as $occurances){
				if($occurances > 1){
					$this->Session->setFlash(__('Cel putin o statie apare de mai multe ori.'), 'error');
					$this->redirect(array('action' => 'view', $id));
				}
			}
			if ($this->StationLine->deleteAll(array('StationLine.line_id' => $id)) && $this->StationLine->saveAll($data['StationLine'])) {
				$this->Session->setFlash(__('Statiile au fost adaugate liniei!'), 'success');
				$this->redirect(array('action' => 'view', $id));
			} else {
				$this->Session->setFlash(__('Statiile nu au putut fi adaugate liniei.'), 'error');
				$this->redirect(array('action' => 'view', $id));
			}
		}
	}

/**
 * admin_edit method
 *
 * @param string $id
 * @return void
 */
	public function admin_edit($id = null) {
		$this->Line->id = $id;
		if (!$this->Line->exists()) {
			throw new NotFoundException(__('Linie invalida'));
		}
		$this->set('line', $this->Line->read(null, $id));
		$this->set('stationLine', $this->StationLine->find('all', array('conditions' => array('StationLine.line_id' => $id), 'order' => 'StationLine.order ASC', 'contain' => array('Station' => array('StationGroup')))));
		$this->set('stations', $this->Station->find('list', array('fields' => array('Station.name_direction'))));
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
		$this->StationLine->id = $id;
		if (!$this->StationLine->exists()) {
			throw new NotFoundException(__('Invalid station line'));
		}
		if ($this->StationLine->delete()) {
			$this->Session->setFlash(__('Station line deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Station line was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
}
