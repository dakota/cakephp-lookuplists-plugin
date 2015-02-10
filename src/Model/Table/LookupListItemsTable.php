<?php

namespace LookupLists\Model\Table;

use Cake\Cache\Cache;
use Cake\Event\Event;
use Cake\ORM\Entity;
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

    public function initialize(array $options = [])
    {
        $this->belongsTo('LookupLists');
        $this->displayField('value');

        parent::initialize($options);
    }


    public function beforeSave(Event $event, Entity $entity)
    {
        $list_values = $this->find();
        $list_values = $list_values
            ->select([
                'new_item_id' => $list_values->func()->max('item_id'),
                'new_display_order' => $list_values->func()->max('display_order')
            ])
            ->where([
                'LookupListItems.lookup_list_id' => $entity->lookup_list_id
            ])
            ->hydrate(false)
            ->first();

        if (!isset($entity->slug))
        {
            $entity->slug = Inflector::slug(strtolower($entity->value), "-");
        }

        if (!isset($entity->item_id))
        {
            $entity->item_id = !is_null($list_values['new_item_id']) ? $list_values['new_item_id'] + 1 : 1;
        }

        if (!isset($entity->display_order))
        {
            $entity->display_order = !is_null($list_values['new_display_order']) ? $list_values['new_display_order'] + 1 : 1;
        }

        if (isset($entity->default) && $entity->default)
        {
            $this->updateAll(
                [
                  'default' => false
                ],
                [
                    'lookup_list_id' => $entity->lookup_list_id
                ]
            );
        }
    }

    public function afterSave(Event $event, Entity $entity)
    {
        if (isset($entity->lookup_list_id))
        {
            $keys = [
                'LookupListDefaultByListID_' . $entity->lookup_list_id,
                'LookupListItemsByListID_' . $entity->lookup_list_id,
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
                    'LookupListItems.lookup_list_id' => $this->data['LookupListItem']['lookup_list_id'],
                    'LookupListItems.slug' => $conditions['slug'],
            ]]);

            if ($find > 0)
                return false;
        }

        return true;
    }

}
