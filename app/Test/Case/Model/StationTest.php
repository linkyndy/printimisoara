<?php
/* Station Test cases generated on: 2012-01-31 12:47:13 : 1328006833*/
App::uses('Station', 'Model');

/**
 * Station Test Case
 *
 */
class StationTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.station', 'app.station_group', 'app.station_line', 'app.line', 'app.time');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		$this->Station = ClassRegistry::init('Station');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Station);

		parent::tearDown();
	}

}
