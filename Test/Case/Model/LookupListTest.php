<?php
App::uses('LookupList', 'LookupLists.Model');

/**
 * LookupList Test Case
 *
 */
class LookupListTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.lookup_lists.lookup_list'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->LookupList = ClassRegistry::init('LookupLists.LookupList');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->LookupList);

		parent::tearDown();
	}

}
