<?php
/* Time Test cases generated on: 2012-01-31 12:48:55 : 1328006935*/
App::uses('Time', 'Model');

/**
 * Time Test Case
 *
 */
class TimeTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.time', 'app.station', 'app.station_group', 'app.station_line', 'app.line');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		$this->Time = ClassRegistry::init('Time');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Time);

		parent::tearDown();
	}

}
