<?php
/**
 * LookupListItemFixture
 *
 */
class LookupListItemFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'unsigned' => true, 'key' => 'primary'),
		'lookup_list_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'unsigned' => true, 'key' => 'index'),
		'item_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'unsigned' => false),
		'slug' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 34, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'value' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 256, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'display_order' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 5, 'unsigned' => true),
		'default' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'idx_unique_2' => array('column' => array('lookup_list_id', 'slug'), 'unique' => 1),
			'idx_unique' => array('column' => array('lookup_list_id', 'item_id'), 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => 1,
			'lookup_list_id' => 1,
			'item_id' => 1,
			'slug' => 'Lorem ipsum dolor sit amet',
			'value' => 'Lorem ipsum dolor sit amet',
			'display_order' => 1,
			'default' => 1
		),
	);

}
