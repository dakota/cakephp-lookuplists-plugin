<?php

namespace LookupLists\Model\Table;

use Cake\Cache\Cache;
use Cake\Event\Event;
use Cake\ORM\Entity;
use Cake\ORM\Query;
use Cake\ORM\Rule\IsUnique;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Utility\Inflector;
use Cake\Validation\Validator;

/**
 * LookupList Model
 *
 */
class LookupListsTable extends Table
{
    public function validationDefault(Validator $validator)
    {
        $validator
            ->notEmpty('name', 'List name is required');

        return $validator;
    }

    public function buildRules(RulesChecker $rules)
    {
        $rules
            ->add(new IsUnique(['slug']), '_uniqueSlug', [
                'errorField' => 'name',
                'message' => 'List name must be unique.'
            ]);

        return $rules;
    }

    public function initialize(array $options = [])
    {
        $this->hasMany('LookupListItems', [
            'className' => 'LookupLists.LookupListItems'
        ]);

        parent::initialize($options);
    }

    public function afterSave(Event $event, Entity $entity)
    {
        Cache::delete("LookupListItems-" . $entity->slug);
        Cache::delete("LookupListDefault-" . $entity->slug);
    }

    public function beforeSave(Event $event, Entity $entity)
    {
        if (!isset($entity->slug)) {
            $entity->slug = Inflector::slug(strtolower($entity->name), "-");
        }
    }

    public function findItems(Query $query, array $options = [])
    {
        if (empty($options['list_slug'])) {
            return false;
        }

        $list_slug = $options['list_slug'];

        $key = "LookupListItems-" . $list_slug;

        $list_items = $this->LookupListItems->find(
            'list',
            [
                'keyField' => 'item_id',
                'valueField' => 'value',
            ]
        )
            ->select(
                [
                    'LookupListItems.item_id',
                    'LookupListItems.value'
                ]
            )
            ->contain(['LookupLists'])
            ->where(
                [
                    'LookupLists.slug' => $list_slug,
                    'LookupListItems.public' => true,
                ]
            )
            ->order(['LookupListItems.display_order' => 'ASC'])
            ->cache($key);

        return $list_items;
    }

    public function getDefault($list_slug)
    {
        $default = null;

        $key = "LookupListDefault-" . $list_slug;

        $list_item = Cache::remember(
            $key,
            function () use ($list_slug) {
                $list_item = $this->LookupListItems
                    ->find()
                    ->select(
                        [
                            'LookupListItems.item_id'
                        ]
                    )
                    ->contain(['LookupLists'])
                    ->where(
                        [
                            'LookupLists.slug' => $list_slug,
                            'LookupListItems.default' => true
                        ]
                    );

                if ($list_item->count() === 0) {
                    $list_item
                        ->where(['LookupLists.slug' => $list_slug], [], true)
                        ->order([
                            'LookupListItems.item_id' => 'ASC'
                        ]);
                }

                return $list_item->first();
            }
        );

        return $list_item ? $list_item->item_id : $default;
    }

    public function getItemId($list_slug, $slug)
    {
        $item_id = null;

        $list_item = $this->LookupListItems
            ->find()
            ->select(['LookupListItems.item_id'])
            ->contain(['LookupLists'])
            ->where(
                [
                    'LookupLists.slug' => $list_slug,
                    'LookupListItems.slug like ' => $slug
                ]
            )
            ->first();

        return $list_item ? $list_item->item_id : $item_id;
    }
}
