<?php
App::uses('AppController', 'Controller');

class TimesController extends AppController {
	
	public $uses = array('Time', 'Station', 'Line', 'StationLine');
	
	public $paginate = array(
		'order' => array(
			'Time.modified' => 'DESC'
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
	
	/**
	 * Convenient method to quickly fetch the
	 * time for a given station_line_id
	 */
	public function admin_quick(){
		if ($this->request->is('post')) {
			$station_line_id = $this->StationLine->idFromStationLineName($this->request->data['Time']['station_line']);

			if ($station_line_id) {
				if ($this->Time->fetchTimes($station_line_id)) {
					$times = $this->Time->saveTimes();
					if ($times !== false) {
						$this->set('times', $times);
					}
				}
				/*if ($this->Time->getOptimizedTime($station_line_id)) {
					$this->set('optimizedTime', $this->Time->optimizedTime);
				}*/
			}
		}
		
		$this->set('station_line_list', $this->StationLine->formatStationLines());
	}

	/*public function admin_view($id = null) {
		$this->Time->id = $id;
		if (!$this->Time->exists()) {
			throw new NotFoundException(__('Invalid time'));
		}
		$this->set('time', $this->Time->read(null, $id));
	}*/
	
	public function admin_stations(){
		$this->set('station_list', $this->Station->find('list', array('fields' => array('Station.name_direction'))));
	}
	
	public function admin_station($id = null){
		// Handle both get (for Station admin_index) and
		// post (for Time admin_stations) requests
		if ($this->request->is('get')) {
			$this->Station->id = $id;
			if (!$this->Station->exists()) {
				throw new NotFoundException(__('Statie invalida'));
			}
		} elseif($this->request->is('post')) {
			if(!$id = $this->Station->field('id', array('name_direction' => $this->request->data['Time']['search']))){
				throw new NotFoundException(__('Statie invalida'));	
			}
		}
		
		$this->set('station', $this->Station->read(null, $id));
		$this->set('lines', $this->Time->station($id));
	}
	
	public function admin_lines(){
		$this->set('line_list', $this->Line->find('list', array('fields' => array('Line.name'))));
	}
	
	public function admin_line($id = null) {
		// Handle both get (for Line admin_index) and
		// post (for Time admin_lines) requests
		if ($this->request->is('get')) {
			$this->Line->id = $id;
			if (!$this->Line->exists()) {
				throw new NotFoundException(__('Linie invalida'));
			}
		} elseif($this->request->is('post')) {
			if(!$id = $this->Line->field('id', array('name' => $this->request->data['Time']['search']))){
				throw new NotFoundException(__('Linie invalida'));	
			}
		}
		
		$this->set('line', $this->Line->read(null, $id));
		$this->set('directions', $this->Time->line($id));
	}
	
	public function admin_coverage(){
		extract($this->Time->coverage());
		$this->set(compact('lines', 'global_coverage_percent'));
	}
	
	/**
	 * Add a an U-type time (user based) or
	 * an T-type time (table based)
	 *
	 * For table based times, remove all
	 * previously saved table times for
	 * the specific station, line and day.
	 */
	public function admin_add($type = 'U') {
		if (!in_array($type, array('U', 'T'))) {
			throw new NotFoundException(__('Tip invalid'));
		}
		
		if ($this->request->is('post')) {
			$data = $this->Time->processAddTimes($this->request->data);
			
			if ($data !== false) {
				// Clear previously defined table times
				if ($type == 'T') {
					$clear = $this->Time->deleteAll(array(
						'Time.station_id' => $data[0]['station_id'],
						'Time.line_id' => $data[0]['line_id'],
						'Time.day' => $data[0]['day'],
						'Time.type' => 'T',
					));
				} else {
					$clear = true;
				}
				
				if ($clear && $this->Time->saveMany($data)) {
					$this->Session->setFlash(__('Timpii au fost salvati!'), 'success');
					$this->redirect(array('action' => 'index'));
				}
			}
			$this->Session->setFlash(__('Timpii nu au putut fi salvati. Te rugam, incearca din nou.'), 'error');
		}
		
		$this->set('station_line_list', $this->StationLine->formatStationLines());
		$this->set('type', $type);
	}

	/*public function admin_edit($id = null) {
		$this->Time->id = $id;
		if (!$this->Time->exists()) {
			throw new NotFoundException(__('Invalid time'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Time->save($this->request->data)) {
				$this->Session->setFlash(__('The time has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The time could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->Time->read(null, $id);
		}
		$stations = $this->Time->Station->find('list');
		$lines = $this->Time->Line->find('list');
		$this->set(compact('stations', 'lines'));
	}

	public function admin_delete($id = null) {
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
