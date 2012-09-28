<?php
App::uses('AppController', 'Controller');

class UsersController extends AppController {
		
/**
 * Everyone
 */

	public function login() {
		if ($this->request->is('post')){
			if ($this->Auth->login()) {
				if($this->Auth->user('role') == 'admin'){
					$this->redirect(array('admin' => true, 'action' => 'dashboard'));
				} elseif($this->Auth->user('role') == 'superuser'){
					$this->redirect(array('superuser' => true, 'action' => 'dashboard'));
				} else {
					$this->redirect($this->Auth->redirect());
				}
			} else {
				$this->Session->setFlash(__('Ai gresit utilizatorul/parola. Mai incearca o data!'));
			}
		}
	}
	
	public function logout() {
		$this->redirect($this->Auth->logout());
	}
	
/**
 * Logged users
 */
 
/**
 * Admin
 */

	public function admin_dashboard() {
		$this->set('vacation', array(
			'is_vacation' => Configure::read('Config.is_vacation'),
			'is_close_to_vacation' => Configure::read('Config.is_close_to_vacation'),
			'vacation_start' => Configure::read('Config.vacation_start'),
			'vacation_end' => Configure::read('Config.vacation_end'),
		));
	}
	
	public function admin_index() {
		$this->User->recursive = 0;
		$this->set('users', $this->paginate());
	}

	public function admin_view($id = null) {
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Utilizator invalid'));
		}
		$this->set('user', $this->User->read(null, $id));
	}

	public function admin_add() {
		if ($this->request->is('post')) {
			$this->User->create();
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('Utilizatorul a fost adaugat!'), 'success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('Utilizatorul nu a putut fi adaugat.'), 'error');
			}
		}
	}

	public function admin_edit($id = null) {
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Utilizator invalid'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('Utilizatorul a fost salvat!'), 'success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('Utilizatorul nu a putut fi salvat.'), 'error');
			}
		} else {
			$this->request->data = $this->User->read(null, $id);
		}
	}

	public function admin_password($id = null) {
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Utilizator invalid'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('Parola a fost salvata!'), 'success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('Parola nu a putut fi salvata.'), 'error');
			}
		} else {
			$this->request->data = $this->User->read(null, $id);
		}
	}

	public function admin_delete($id = null) {
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Utilizator invalid'));
		}
		if ($this->User->delete()) {
			$this->Session->setFlash(__('Utilizatorul a fost sters!'), 'success');
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Utilizatorul nu a putut fi sters.'), 'error');
		$this->redirect(array('action' => 'index'));
	}

/**
 * Superuser
 */
 	
	public function superuser_dashboard() {
		
	}
}