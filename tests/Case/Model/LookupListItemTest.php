<?php

namespace Test\Case\Model;

use Cake\TestSuite\TestCase;
use LookupLists\Model\LookupListItem;

/**
 * LookupListItem Test Case
 *
 */
class LookupListItemTest extends TestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = array(
		'plugin.lookup_lists.lookup_list_items',
		'plugin.lookup_lists.lookup_lists'
	);

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->LookupListItem = ClassRegistry::init('LookupLists.LookupListItem');
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->LookupListItem);

        parent::tearDown();
    }
    
    public function testAdd()
    {
        
    }
    

}
