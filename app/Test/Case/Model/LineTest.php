<?php
/* Line Test cases generated on: 2012-01-31 12:35:29 : 1328006129*/
App::uses('Line', 'Model');

/**
 * Line Test Case
 *
 */
class LineTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.line', 'app.time', 'app.station_line');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		$this->Line = ClassRegistry::init('Line');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Line);

		parent::tearDown();
	}

}
