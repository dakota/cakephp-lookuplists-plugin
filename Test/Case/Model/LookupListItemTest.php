<?php
App::uses('LookupListItem', 'LookupLists.Model');

/**
 * LookupListItem Test Case
 *
 */
class LookupListItemTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.lookup_lists.lookup_list_item',
		'plugin.lookup_lists.lookup_list'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->LookupListItem = ClassRegistry::init('LookupLists.LookupListItem');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->LookupListItem);

		parent::tearDown();
	}

}
