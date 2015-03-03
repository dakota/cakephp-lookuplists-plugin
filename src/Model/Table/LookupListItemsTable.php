<?php

namespace LookupLists\Model\Table;

use Cake\Cache\Cache;
use Cake\Event\Event;
use Cake\ORM\Entity;
use Cake\ORM\Rule\IsUnique;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Utility\Inflector;
use Cake\Validation\Validator;

/**
 * LookupListItem Model
 *
 * @property LookupList $LookupList
 */
class LookupListItemsTable extends Table
{

    public function validationDefault(Validator $validator)
    {
        $validator
            ->notEmpty('value', 'Item value is required.')
            ->add('default', [
                'boolean' => [
                    'rule' => 'boolean'
                ]
            ])
            ->add('public', [
                'boolean' => [
                    'rule' => 'boolean'
                ]
            ]);

        return $validator;
    }

    public function buildRules(RulesChecker $rules)
    {
        $rules
            ->add(
                new IsUnique(['slug']),
                '_uniqueSlug',
                [
                    'errorField' => 'value',
                    'message' => 'Item slug must be unique.'
                ]
            );

        return $rules;
    }

    public function initialize(array $options = [])
    {
        $this->belongsTo('LookupLists', [
            'className' => 'LookupLists.LookupLists'
        ]);
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

        if (!isset($entity->slug)) {
            $entity->slug = Inflector::slug(strtolower($entity->value), "-");
        }

        if (!isset($entity->item_id)) {
            $entity->item_id = !is_null($list_values['new_item_id']) ? $list_values['new_item_id'] + 1 : 1;
        }

        if (!isset($entity->display_order)) {
            $entity->display_order = !is_null($list_values['new_display_order']) ? $list_values['new_display_order'] + 1 : 1;
        }

        if (isset($entity->default) && $entity->default) {
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
        if (isset($entity->lookup_list_id)) {
            $keys = [
                'LookupListItems' . $entity->lookup_list->slug,
                'LookupListDefault' . $entity->lookup_list->slug,
            ];

            foreach ($keys as $key) {
                Cache::delete($key);
            }
        }
    }

    public function afterDelete(Event $event, Entity $entity)
    {
        if (isset($entity->lookup_list_id)) {
            $keys = [
                'LookupListItems-' . $entity->lookup_list->slug,
                'LookupListDefault-' . $entity->lookup_list->slug,
            ];

            foreach ($keys as $key) {
                Cache::delete($key);
            }
        }
    }

    public function uniquePerList($conditions)
    {
        if (isset($this->data['LookupListItem']['lookup_list_id'])) {
            $find = $this->find('count', [
                'conditions' => [
                    'LookupListItems.lookup_list_id' => $this->data['LookupListItem']['lookup_list_id'],
                    'LookupListItems.slug' => $conditions['slug'],
                ]
            ]);

            if ($find > 0) {
                return false;
            }
        }

        return true;
    }

}
