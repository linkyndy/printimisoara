<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2011, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2011, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       Cake.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('Controller', 'Controller');

/**
 * This is a placeholder class.
 * Create the same file in app/Controller/AppController.php
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package       Cake.Controller
 * @link http://book.cakephp.org/view/957/The-App-Controller
 */
class AppController extends Controller {
	public $components = array('Session', 'RequestHandler', 'Auth');
	public $helpers = array('Html' => array('className' => 'CustomHtml'), 'Form', 'Session', 'Js', 'Text', 'Time');
	
	public function beforeFilter(){
		parent::beforeFilter();
		
		$this->loadModel('Config');
		$this->Config->getConfig();
		
		$this->loadModel('Maintenance');
		$this->Maintenance->getMaintenance();
		
		$this->Auth->loginAction = array('admin' => false, 'superuser' => false, 'controller' => 'users', 'action' => 'login');
		$this->Auth->authorize = array('Controller');
		
		$this->setLayout();
	}
	
	public function isAuthorized($user = null){
		if(isset($this->request->params['admin'])){
			return $user['role'] === 'admin';	
		} elseif(isset($this->request->params['superuser'])){
			return in_array($user['role'], array('admin', 'superuser'));	
		}
		return true;
	}
	
	public function setLayout(){
		if(isset($this->request->params['admin'])){
			$this->layout = 'admin';	
		} elseif(isset($this->request->params['superuser'])){
			$this->layout = 'superuser';	
		} else {
			$this->layout = 'default3';
		}
	}
}
