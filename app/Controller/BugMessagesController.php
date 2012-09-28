<?php
App::uses('AppController', 'Controller');

class BugMessagesController extends AppController {
	
	public $uses = array('BugMessage', 'Bug');
	
	public $paginate = array(
		'order' => array(
			'BugMessage.created' => 'ASC'
		)
	);
	
	public function beforeFilter(){
		parent::beforeFilter();
		$this->Auth->allow('index', 'view');	
	}

/**
 * Everyone
 */
	
/**
 * Logged users
 */

	public function add($bug_id = null) {
		if($this->request->is('ajax') && $this->request->is('post')){
			$this->Bug->id = $bug_id;
			if (!$this->Bug->exists()) {
				$this->set('response', false);
			} else {
				$this->BugMessage->create();
				$this->BugMessage->set($this->request->data);
				if($this->BugMessage->validates()){
					$this->request->data['BugMessage']['bug_id'] = $bug_id;
					$this->request->data['BugMessage']['user_id'] = $this->Auth->user('id');
					if($this->BugMessage->save($this->request->data)){
						$this->set('response', array('message' => $this->BugMessage->findById($this->BugMessage->id)));
					} else {
						$this->set('response', false);
					}
				} else {
					$this->set('response', array('errors' => $this->BugMessage->validationErrors));	
				}
			}
		} else {
			$this->set('response', false);
		}
		$this->set('_serialize', 'response');
	}

	public function edit($bug_id = null, $id = null) {
		$this->Bug->id = $bug_id;
		if (!$this->Bug->exists()) {
			throw new NotFoundException(__('Bug invalid'));
		}
		$this->BugMessage->id = $id;
		if (!$this->BugMessage->exists()) {
			throw new NotFoundException(__('Mesaj invalid'));
		}
		if ($this->BugMessage->field('user_id') != $this->Auth->user('id')) {
			throw new NotFoundException(__('Nu ai dreptul sa editezi acest mesaj!'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {			
			if ($this->BugMessage->save($this->request->data)) {
				$this->Session->setFlash(__('Modificarile au fost salvate!'), 'success');
				$this->redirect(array('controller' => 'bugs', 'action' => 'view', $bug_id));
			} else {
				$this->Session->setFlash(__('Modificarile nu au putut fi salvate.'), 'error');
			}
		} else {
			$this->request->data = $this->BugMessage->read(null, $id);
		}
	}
 
/**
 * Admin
 */

	public function admin_add($bug_id = null) {		
		if($this->request->is('ajax') && $this->request->is('post')){
			$this->Bug->id = $bug_id;
			if (!$this->Bug->exists()) {
				$this->set('response', false);
			} else {
				$this->BugMessage->create();
				$this->BugMessage->set($this->request->data);
				if($this->BugMessage->validates()){
					$this->request->data['BugMessage']['bug_id'] = $bug_id;
					$this->request->data['BugMessage']['user_id'] = $this->Auth->user('id');
					if($this->BugMessage->save($this->request->data)){
						$this->set('response', array('message' => $this->BugMessage->findById($this->BugMessage->id)));
					} else {
						$this->set('response', false);
					}
				} else {
					$this->set('response', array('errors' => $this->BugMessage->validationErrors));	
				}
			}
		} elseif ($this->request->is('post')) {
			//$this->set('response', false);
			
			$this->Bug->id = $bug_id;
			if (!$this->Bug->exists()) {
				throw new NotFoundException(__('Bug invalid'));
			} else {
				$this->request->data['BugMessage']['bug_id'] = $bug_id;
				$this->request->data['BugMessage']['user_id'] = $this->Auth->user('id');
				if($this->BugMessage->save($this->request->data)){
					$this->Session->setFlash(__('Mesajul a fost salvat!'), 'success');
					$this->redirect(array('controller' => 'bugs', 'action' => 'view', $this->Bug->id));
				} else {
					$this->redirect(array('controller' => 'bugs', 'action' => 'view', $this->Bug->id));
				}
			}
		}
		$this->set('_serialize', 'response');
	}

	public function admin_edit($bug_id = null, $id = null) {
		$this->Bug->id = $bug_id;
		if (!$this->Bug->exists()) {
			throw new NotFoundException(__('Bug invalid'));
		}
		$this->BugMessage->id = $id;
		if (!$this->BugMessage->exists()) {
			throw new NotFoundException(__('Mesaj invalid'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {			
			if ($this->BugMessage->save($this->request->data)) {
				$this->Session->setFlash(__('Modificarile au fost salvate!'), 'success');
				$this->redirect(array('controller' => 'bugs', 'action' => 'view', $bug_id));
			} else {
				$this->Session->setFlash(__('Modificarile nu au putut fi salvate.'), 'error');
			}
		} else {
			$this->request->data = $this->BugMessage->read(null, $id);
		}
	}

	public function admin_delete($bug_id = null, $id = null) {
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		$this->Bug->id = $bug_id;
		if (!$this->Bug->exists()) {
			throw new NotFoundException(__('Bug invalid'));
		}
		$this->BugMessage->id = $id;
		if (!$this->BugMessage->exists()) {
			throw new NotFoundException(__('Mesaj invalid'));
		}
		if ($this->BugMessage->delete()) {
			$this->Session->setFlash(__('Mesajul a fost sters!'), 'success');
			$this->redirect(array('controller' => 'bugs', 'action' => 'view', $bug_id));
		}
		$this->Session->setFlash(__('Mesajul nu a putut fi sters.'), 'error');
		$this->redirect(array('controller' => 'bugs', 'action' => 'view', $bug_id));
	}

/**
 * Superuser
 */
 
	public function superuser_add($bug_id = null){
		$this->redirect(array('superuser' => false, 'action' => 'add', $bug_id));	
	}
	
	public function superuser_edit($bug_id = null, $id = null){
		$this->redirect(array('superuser' => false, 'action' => 'edit', $bug_id, $id));	
	}
}
