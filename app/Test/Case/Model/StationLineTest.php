<?php
/* StationLine Test cases generated on: 2012-01-31 12:39:26 : 1328006366*/
App::uses('StationLine', 'Model');

/**
 * StationLine Test Case
 *
 */
class StationLineTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.station_line', 'app.station', 'app.line', 'app.time');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		$this->StationLine = ClassRegistry::init('StationLine');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->StationLine);

		parent::tearDown();
	}

}
