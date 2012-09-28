<?php

App::uses('HtmlHelper', 'View/Helper');

class CustomHtmlHelper extends HtmlHelper {
	protected $_lineFontSizes = array('1' => 'big', '2' => 'medium', '3' => 'small');
	protected $_isBar = false;
	
	protected function _isBar(&$line){
		$line = h($line);
		if (strpos($line, 'barat')) {
			$line = str_replace('barat', '', $line);
			$this->_isBar = true;
		} else {
			$this->_isBar = false;
		}
	}
	
	public function line($line, $colour = 'default', $id = null){
		$this->_isBar($line);
		
		return 
			$id ?
			$this->link($line, array('controller' => 'lines', 'action' => 'view', $id), array('class' => 'line line-' . $colour . ' line-' . $this->_lineFontSizes[strlen($line)] . (($this->_isBar) ? ' line-bar' : null), 'escape' => false)) :
			'<span class="line line-' . $colour . ' line-' . $this->_lineFontSizes[strlen($line)]  . (($this->_isBar) ? ' line-bar' : null) . '">' . $line . '</span>'
		;
	}
}
