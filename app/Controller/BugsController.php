<?php
App::uses('AppController', 'Controller');

class BugsController extends AppController {
	
	public $paginate = array(
		'order' => array(
			'Bug.created' => 'DESC'
		)
	);
	
	public function beforeFilter(){
		parent::beforeFilter();
		$this->Auth->allow('index', 'view');	
	}

/**
 * Everyone
 */

	public function index($status = null) {
		$this->Bug->recursive = 1;
		if($status){
			$this->set('bugs', $this->paginate('Bug', array('Bug.status' => $status)));
		} else{
			$this->set('bugs', $this->paginate());
		}
		$this->set('bugsByStatus', array(
			'new' => $this->Bug->findAllByStatus('new', array('Bug.id'), array(), null, null, -1),
			'pending' => $this->Bug->findAllByStatus('pending', array('Bug.id'), array(), null, null, -1),
			'invalid' => $this->Bug->findAllByStatus('invalid', array('Bug.id'), array(), null, null, -1),
			'resolved' => $this->Bug->findAllByStatus('resolved', array('Bug.id'), array(), null, null, -1),
			'future' => $this->Bug->findAllByStatus('future', array('Bug.id'), array(), null, null, -1)
		));
	}
	
	public function view($id = null) {
		$this->Bug->id = $id;
		if (!$this->Bug->exists()) {
			throw new NotFoundException(__('Bug invalid'));
		}
		$this->Bug->recursive = 2;
		$this->set('bug', $this->Bug->read(null, $id));
	}
	
/**
 * Logged users
 */

	public function add() {
		if ($this->request->is('post')) {
			$this->Bug->create();
			$this->request->data['Bug']['user_id'] = $this->Auth->user('id');
			$this->request->data['Bug']['status'] = 'new';
			if ($this->Bug->save($this->request->data)) {
				$this->Session->setFlash(__('Bug-ul a fost salvat!'), 'success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('Bug-ul nu a putut fi salvat.'), 'error');
			}
		}
	}

	public function edit($id = null) {
		$this->Bug->id = $id;
		if (!$this->Bug->exists()) {
			throw new NotFoundException(__('Bug invalid'));
		}
		if ($this->Bug->field('user_id') != $this->Auth->user('id')) {
			throw new NotFoundException(__('Nu ai dreptul sa editezi acest bug!'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {			
			if ($this->Bug->save($this->request->data)) {
				$this->Session->setFlash(__('Modificarile au fost salvate!'), 'success');
				$this->redirect(array('action' => 'view', $id));
			} else {
				$this->Session->setFlash(__('Modificarile nu au putut fi salvate.'), 'error');
			}
		} else {
			$this->request->data = $this->Bug->read(null, $id);
		}
	}
 
/**
 * Admin
 */
 
	public function admin_index($status = null) {
		$this->Bug->recursive = 1;
		if($status){
			$this->set('bugs', $this->paginate('Bug', array('Bug.status' => $status)));
		} else{
			$this->set('bugs', $this->paginate());
		}
	}
	
	public function admin_view($id = null) {
		$this->Bug->id = $id;
		if (!$this->Bug->exists()) {
			throw new NotFoundException(__('Bug invalid'));
		}
		$this->Bug->recursive = 2;
		$this->set('bug', $this->Bug->read(null, $id));
	}

	public function admin_add() {
		if ($this->request->is('post')) {
			$this->Bug->create();
			$this->request->data['Bug']['user_id'] = $this->Auth->user('id');
			$this->request->data['Bug']['status'] = 'new';
			if ($this->Bug->save($this->request->data)) {
				$this->Session->setFlash(__('Bug-ul a fost salvat!'), 'success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('Bug-ul nu a putut fi salvat.'), 'error');
			}
		}
	}
	
	public function admin_update_status($id = null, $status) {
		$statuses = array('new', 'pending', 'invalid', 'resolved', 'future');
		
		$this->Bug->id = $id;
		if (!$this->Bug->exists()) {
			throw new NotFoundException(__('Bug invalid'));
		}
		if (!in_array($status, $statuses)) {
			throw new NotFoundException(__('Status invalid'));
		}
		
		$data['Bug']['status'] = $status;	
		if ($this->Bug->save($data)) {
			$this->Session->setFlash(__('Statusul a fost schimbat!'), 'success');
			$this->redirect(array('action' => 'view', $id));
		} else {
			$this->Session->setFlash(__('Modificarile nu a putut fi salvat.'), 'error');
			throw new NotFoundException(__('Bug invalid'));
		}
	}
	
	public function admin_edit($id = null) {
		$this->Bug->id = $id;
		if (!$this->Bug->exists()) {
			throw new NotFoundException(__('Bug invalid'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {		
			if ($this->Bug->save($this->request->data)) {
				$this->Session->setFlash(__('Modificarile au fost salvate!'), 'success');
				$this->redirect(array('action' => 'view', $id));
			} else {
				$this->Session->setFlash(__('Modificarile nu au putut fi salvate.'), 'error');
			}
		} else {
			$this->request->data = $this->Bug->read(null, $id);
		}
	}

	public function admin_delete($id = null) {
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		$this->Bug->id = $id;
		if (!$this->Bug->exists()) {
			throw new NotFoundException(__('Bug invalid'));
		}
		if ($this->Bug->delete()) {
			$this->Session->setFlash(__('Bug-ul a fost sters!'), 'success');
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Bug-ul nu a putut fi sters.'), 'error');
		$this->redirect(array('action' => 'index'));
	}

/**
 * Superuser
 */
 
 	public function superuser_index($status = null){
		$this->redirect(array('superuser' => false, 'action' => 'index', $status));	
	}
	
	public function superuser_view($id = null){
		$this->redirect(array('superuser' => false, 'action' => 'view', $id));	
	}
	
	public function superuser_add(){
		$this->redirect(array('superuser' => false, 'action' => 'add'));	
	}
	
	public function superuser_edit($id = null){
		$this->redirect(array('superuser' => false, 'action' => 'edit', $id));	
	}
	
	public function superuser_report(){
		if($this->request->is('ajax') && $this->request->is('post')){
			$this->Bug->create();
			$this->Bug->set($this->request->data);
			if($this->Bug->validates()){
				$this->request->data['Bug']['user_id'] = $this->Auth->user('id');
				$this->request->data['Bug']['status'] = 'new';
				if($this->Bug->save($this->request->data)){
					$this->set('response', true);
				} else {
					$this->set('response', false);
				}
			} else {
				$this->set('response', $this->Bug->validationErrors);	
			}
		} else {
			$this->set('response', false);
		}
		$this->set('_serialize', 'response');
	}
}
