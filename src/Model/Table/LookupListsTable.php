<?php

namespace LookupLists\Model\Table;

use Cake\Cache\Cache;
use Cake\ORM\Table;
use Cake\Utility\Inflector;

/**
 * LookupList Model
 *
 */
class LookupListsTable extends Table
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
    public $validate = [
        'slug' => [
            'notEmpty' => [
                'rule' => ['notEmpty'],
                'message' => 'slug is required',
                'allowEmpty' => false,
                'required' => false,
            ],
            'unique' => [
                'rule' => 'isUnique',
                'message' => 'List name needs to be Unique',
            ],
        ],
        'name' => [
            'notEmpty' => [
                'rule' => ['notEmpty'],
                'message' => 'List Name is required',
                'required' => true,
                'on' => 'create',
            ],
        ],
        'public' => [
            'boolean' => [
                'rule' => ['boolean'],
            ],
        ],
    ];

    public function initialize(array $options = [])
    {
        $this->hasMany('LookupListItems');

        parent::initialize($options);
    }

    public function beforeSave($options = [])
    {
        parent::beforeSave($options);

        if (!isset($this->data["LookupList"]["slug"]))
        {
            $this->data["LookupList"]["slug"] = Inflector::slug(strtolower($this->data["LookupList"]["name"]), "-");
        }

        return $this->validates();
    }

    public function listItems($list_slug)
    {
        $key = "LookupListIDbySlug_" . $list_slug;

        $list_id = $this->_listIdBySlug($list_slug);

        $list_items = [];

        if ($list_id)
        {
            $key = "LookupListItemsByListID_" . $list_id['LookupList']['id'];

            if (!$list_items = Cache::read($key))
            {
                $list_items = $this->LookupListItems->find('list', [
                    'conditions' => [
                        'LookupListItem.lookup_list_id' => $list_id['LookupList']['id'],
                        'LookupListItem.public' => true,
                    ],
                    'order' => ['LookupListItem.display_order' => 'ASC'],
                    'fields' => ['LookupListItem.item_id', 'LookupListItem.value'],
                    'cache' => 'LookupListsListItems_' . $list_slug,
                ]);
                Cache::write($key, $list_items);
            }
        }

        return $list_items;
    }

    public function getDefault($list_slug)
    {
        $default = null;

        $list_id = $this->_listIdBySlug($list_slug);

        if (!$list_id)
        {
            return $default;
        }

        $key = "LookupList_DefaultByListID_" . $list_id;

        $list_item = Cache::remember($key, function () use ($list_id) {
            $list_item = $this->LookupListItems
                ->find()
                ->select([
                    'LookupListItems.item_id'
                ])
                ->where([
                    'LookupListItems.lookup_list_id' => $list_id,
                    'LookupListItems.default' => true
                ])
                ->first();

            if (!$list_item) {
                $list_item = $this->LookupListItems
                    ->find()
                    ->select([
                        'LookupListItems.item_id'
                    ])
                    ->where([
                        'LookupListItems.lookup_list_id' => $list_id,
                    ])
                    ->order([
                        'LookupListItems.item_id' => 'ASC'
                    ])
                    ->first();
            }

            return $list_item;
        });

        if ($list_item)
        {
            return $list_item->item_id;
        }

        return $default;
    }

    public function getItemId($list_slug, $slug)
    {
        $item_id = null;

        $list_id = $this->_listIdBySlug($list_slug);

        if ($list_id)
        {
            $list_item = $this->LookupListItems
                ->find()
                ->select(['LookupListItems.item_id'])
                ->where([
                    'LookupListItems.lookup_list_id' => $list_id,
                    'LookupListItems.slug like ' => $slug
                ])
                ->first();

            if ($list_item)
            {
                return $list_item->item_id;
            }
        }

        return $item_id;
    }

    private function _listIdBySlug($list_slug)
    {
        $key = "LookupList_listIdBySlug_" . $list_slug;

        $list_id = $this->find()
            ->select([
                'LookupLists.id'
            ])
            ->where([
                'LookupLists.slug' => $list_slug
            ])
            ->cache($key)
            ->first();


        return $list_id->id;
    }

}
