<?php

namespace Test\Case\Model;

use Cake\TestSuite\TestCase;
use LookupLists\Model\LookupList;

/**
 * LookupList Test Case
 *
 */
class LookupListTest extends TestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = array(
		'plugin.lookup_lists.lookup_lists',
		'plugin.lookup_lists.lookup_list_items'
	);

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->LookupList = ClassRegistry::init('LookupLists.LookupList');
        $this->LookupListItem = ClassRegistry::init('LookupLists.LookupListItem');
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->LookupList);

        parent::tearDown();
    }
    
    public function testListAdd()
    {
        $data = array('LookupList' => array(
            'name' => 'Test New List Item',
        ));
        
        $this->LookupList->create();
        $response = $this->LookupList->save($data);
        
        //debug($response);
        
        $this->assertTrue(!empty($response), 'Record not created');
        
        $listId = $response["LookupList"]["id"];
        
        $items = array('Active', 'Suspended', 'Deleted');
        
        foreach($items as $item)
        {
            $item_data = array('LookupListItem' => array(
                'lookup_list_id' => $listId,
                'value' => $item,
            ));
            
            $this->LookupListItem->create();
            $item_response = $this->LookupListItem->save($item_data);
            
            $this->assertTrue(!empty($item_response), 'Record not created');
            
            //debug($item_response);
            
        }
        
    }

    public function testListItems()
    {
        $this->testListAdd();

        $ListItems = $this->LookupList->listItems('Test_New_List_Item');
        
        $default = $this->LookupList->getDefault('Test_New_List_Item');
        
        $item_id = $this->LookupList->getItemId('Test_New_List_Item', 'active');
        
        $this->assertEqual($item_id, 1, 'Invalid Item Id');
        
    }

}
