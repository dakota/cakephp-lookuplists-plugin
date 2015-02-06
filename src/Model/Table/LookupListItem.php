<?php

namespace LookupLists\Model\Table;

use Cake\Cache\Cache;
use Cake\ORM\Table;
use Cake\Utility\Inflector;

/**
 * LookupListItem Model
 *
 * @property LookupList $LookupList
 */
class LookupListItemsTable extends Table
{

    /**
     * Display field
     *
     * @var string
     */
    public $displayField = 'value';

    /**
     * Validation rules
     *
     * @var array
     */
    public $validate = [
        'lookup_list_id' => [
            'numeric' => [
                'rule' => ['numeric'],
            ],
        ],
        'item_id' => [
            'numeric' => [
                'rule' => ['numeric'],
            ],
        ],
        'slug' => [
            'notEmpty' => [
                'rule' => ['notEmpty'],
                'on' => 'create', // Limit validation to 'create' or 'update' operations
            ],
            'uniquePerList' => [
                'rule' => ['uniquePerList'],
                'message' => 'List slug must be unique',
                'on' => 'create',
            ],
        ],
        'value' => [
            'notEmpty' => [
                'rule' => ['notEmpty'],
            ],
        ],
        'display_order' => [
            'numeric' => [
                'rule' => ['numeric'],
            ],
        ],
        'default' => [
            'boolean' => [
                'rule' => ['boolean'],
            ],
        ],
        'public' => [
            'boolean' => [
                'rule' => ['boolean'],
            ],
        ],
    ];

    /**
     * belongsTo associations
     *
     * @var array
     */
    public $belongsTo = [
        'LookupList' => [
            'className' => 'LookupList',
            'foreignKey' => 'lookup_list_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ]
    ];

    public function beforeSave($options = [])
    {
        parent::beforeSave($options);

        $list_values = $this->find('first', [
            'conditions' => ['LookupListItem.lookup_list_id' => $this->data["LookupListItem"]['lookup_list_id']],
            'fields' => ['max(item_id)+1 as new_item_id', 'max(display_order)+1 as new_display_order'],
        ]);
        
        if (!isset($this->data["LookupListItem"]['slug']))
        {
            $this->data["LookupListItem"]['slug'] = Inflector::slug(strtolower($this->data["LookupListItem"]['value']), "-");
        }

        if (!isset($this->data["LookupListItem"]['item_id']))
        {
            $this->data["LookupListItem"]['item_id'] = !is_null($list_values[0]['new_item_id']) ? $list_values[0]['new_item_id'] : 1;
        }

        if (!isset($this->data["LookupListItem"]['display_order']))
        {
            $this->data["LookupListItem"]['display_order'] = !is_null($list_values[0]['new_display_order']) ? $list_values[0]['new_display_order'] : 1;
        }

        if (isset($this->data["LookupListItem"]['default']))
        {
            if ($this->data["LookupListItem"]['default'])
            {
                $this->updateAll(["LookupListItem.default" => false], ['LookupListItem.lookup_list_id' => $this->data["LookupListItem"]['lookup_list_id']]);
            }
        }

        return $this->validates();
    }

    public function afterSave($created, $options = [])
    {
        parent::afterSave($created, $options);

        if (isset($this->data["LookupListItem"]["lookup_list_id"]))
        {
            $keys = [
                'LookupListDefaultByListID_' . $this->data["LookupListItem"]["lookup_list_id"],
                'LookupListItemsByListID_' . $this->data["LookupListItem"]["lookup_list_id"],
            ];

            foreach ($keys as $key)
                Cache::delete($key);
        }
    }

    public function uniquePerList($conditions)
    {
        if (isset($this->data['LookupListItem']['lookup_list_id']))
        {
            $find = $this->find('count', ['conditions' => [
                    'LookupListItem.lookup_list_id' => $this->data['LookupListItem']['lookup_list_id'],
                    'LookupListItem.slug' => $conditions['slug'],
            ]]);

            if ($find > 0)
                return false;
        }

        return true;
    }

}
