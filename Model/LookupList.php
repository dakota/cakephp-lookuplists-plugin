<?php

App::uses('LookupListsAppModel', 'LookupLists.Model');

/**
 * LookupList Model
 *
 */
class LookupList extends LookupListsAppModel
{

    /**
     * Display field
     *
     * @var string
     */
    public $displayField = 'name';

    /**
     * Validation rules
     *
     * @var array
     */
    public $validate = array(
        'slug' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => 'slug is required',
                'allowEmpty' => false,
                'required' => false,
            ),
            'unique' => array(
                'rule' => 'isUnique',
                'message' => 'List name needs to be Unique',
            ),
        ),
        'name' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => 'List Name is required',
                'required' => true,
                'on' => 'create',
            ),
        ),
        'public' => array(
            'boolean' => array(
                'rule' => array('boolean'),
            ),
        ),
    );
    public $hasMany = array(
        'LookupListItem' => array(
            'className' => 'LookupListItem',
            'foreignKey' => 'lookup_list_id',
            'order' => 'LookupListItem.display_order',
            'counterCache' => true,
            'dependent' => true,
        )
    );

    public function beforeSave($options = array())
    {
        parent::beforeSave($options);

        if (!isset($this->data["LookupList"]["slug"]))
        {
            $this->data["LookupList"]["slug"] = Inflector::slug($this->data["LookupList"]["name"]);
        }

        return $this->validates();
    }

    public function listItems($list_slug)
    {
        $key = "LookupListIDbySlug_" . $list_slug;

        $list_id = $this->_listIdBySlug($list_slug);

        $list_items = array();

        if ($list_id)
        {
            $key = "LookupListItemsByListID_" . $list_id['LookupList']['id'];

            if (!$list_items = Cache::read($key))
            {
                $list_items = $this->LookupListItem->find('list', array(
                    'conditions' => array(
                        'LookupListItem.lookup_list_id' => $list_id['LookupList']['id'],
                        'LookupListItem.public' => true,
                    ),
                    'order' => array('LookupListItem.display_order' => 'ASC'),
                    'fields' => array('LookupListItem.item_id', 'LookupListItem.value'),
                    'cache' => 'LookupListsListItems_' . $list_slug,
                ));
                Cache::write($key, $list_items);
            }
        }

        return $list_items;
    }

    public function getDefault($list_slug)
    {
        $default = null;

        $list_id = $this->_listIdBySlug($list_slug);

        if ($list_id)
        {
            $key = "LookupList_DefaultByListID_" . $list_id['LookupList']['id'];

            if (!$list_item = Cache::read($key))
            {
                $list_item = $this->LookupListItem->find('first', array(
                    'recursive' => -1,
                    'conditions' => array('LookupListItem.lookup_list_id' => $list_id['LookupList']['id'], 'LookupListItem.default' => true),
                    'fields' => array('LookupListItem.item_id'),
                ));

                Cache::write($key, $list_item);
            }

            if ($list_item)
            {
                return $list_item["LookupListItem"]["item_id"];
            }
        }

        return $default;
    }

    public function getItemId($list_slug, $slug)
    {
        $item_id = null;

        $list_id = $this->_listIdBySlug($list_slug);

        if ($list_id)
        {
            $list_item = $this->LookupListItem->find('first', array(
                'recursive' => -1,
                'conditions' => array('LookupListItem.lookup_list_id' => $list_id['LookupList']['id'], 'LookupListItem.slug' => $slug),
                'fields' => array('LookupListItem.item_id'),
            ));

            if ($list_item)
            {
                return $list_item["LookupListItem"]["item_id"];
            }
        }

        return $item_id;
    }

    private function _listIdBySlug($list_slug)
    {
        $key = "LookupList_listIdBySlug_" . $list_slug;

        if (!$list_id = Cache::read($key))
        {
            $list_id = $this->find('first', array('recursive' => -1, 'fields' => 'LookupList.id', 'conditions' => array('LookupList.slug' => $list_slug)));
            Cache::write($key, $list_id);
        }

        return $list_id;
    }
    
    

}
