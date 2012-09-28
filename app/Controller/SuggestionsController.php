<?php
App::uses('AppController', 'Controller');

class SuggestionsController extends AppController {
	
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
		$this->Suggestion->recursive = 0;
		$this->paginate = array('formatted');
		$this->set('suggestions', $this->paginate());
	}
	
	public function admin_accept($id = null) {
		$this->Suggestion->id = $id;
		if (!$this->Suggestion->exists()) {
			throw new NotFoundException(__('Sugestie invalida'));
		}
		
		if($this->Suggestion->accept() && $this->Suggestion->delete()){
			$this->Session->setFlash(__('Sugestia a fost acceptata!'), 'success');
			$this->redirect(array('action' => 'index'));
		} else {
			$this->Session->setFlash(__('Sugestia nu a putut fi acceptata.'), 'error');
			$this->redirect(array('action' => 'index'));
		}
	}

	public function admin_reject($id = null) {
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		$this->Suggestion->id = $id;
		if (!$this->Suggestion->exists()) {
			throw new NotFoundException(__('Sugestie invalida'));
		}
		if ($this->Suggestion->delete()) {
			$this->Session->setFlash(__('Sugestia a fost stearsa!'), 'success');
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Sugestia nu a putut fi stearsa.'), 'error');
		$this->redirect(array('action' => 'index'));
	}
	
/**
 * Superuser
 */

}
