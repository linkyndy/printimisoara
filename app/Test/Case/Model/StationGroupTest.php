<?php
/* StationGroup Test cases generated on: 2012-01-31 12:37:49 : 1328006269*/
App::uses('StationGroup', 'Model');

/**
 * StationGroup Test Case
 *
 */
class StationGroupTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.station_group', 'app.station');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		$this->StationGroup = ClassRegistry::init('StationGroup');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->StationGroup);

		parent::tearDown();
	}

}
