<?php

namespace LookupLists\Model\Behavior;

use Cake\Cache\Cache;
use Cake\Collection\Collection;
use Cake\Event\Event;
use Cake\ORM\Behavior;
use Cake\ORM\Entity;
use Cake\ORM\Query;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

class ListsBehavior extends Behavior
{

    protected $_defaultConfig = [
        'show_display_field' => true,
        'fields' => [],
    ];

    /**
     * @var \LookupLists\Model\Table\LookupListsTable
     */
    protected $_LookupLists;

    public function initialize(array $config)
    {
        $this->_LookupLists = TableRegistry::get('LookupLists.LookupLists');
    }

    public function beforeFind(Event $event, Query $query)
    {
        $list_data = [];
        $fields = $this->config('fields');

        if (empty($fields)) {
            return;
        }

        foreach ($fields as $field => $db_list_name) {
            $key = "LookupListData_" . $event->subject()->alias() . "_" . $field;

            $list_name = null;

            if (is_array($db_list_name)) {
                if (isset($db_list_name['list'])) {
                    $list_name = $db_list_name['list'];
                }
            }

            if (is_null($list_name)) {
                $list_name = $db_list_name;
            }

            $list_data[$field] = $this->_LookupLists
                ->find()
                ->where([
                    'LookupLists.slug' => $list_name
                ])
                ->contain('LookupListItems')
                ->cache($key)
                ->first();
        }

        if (empty($list_data)) {
            return;
        }

        $query
            ->formatResults(function ($results) use ($list_data, $fields) {
               return $results
                   ->map(function ($entity) use ($list_data, $fields) {
                        foreach ($list_data as $list_name => $list_entity) {
                            if (empty($entity->{$list_name})) {
                                continue;
                            }
                            $slug_field = "{$list_name}_slug";
                            $value_field = "{$list_name}_value";

                            if (isset($fields[$list_name]['slug_field'])) {
                                $slug_field = $fields[$list_name]['slug_field'];
                            }

                            if (isset($fields[$list_name]['value_field'])) {
                                $value_field = $fields[$list_name]['value_field'];
                            }

                            $entity->{$slug_field} = null;
                            $entity->{$value_field} = null;

                            if (empty($list_entity->lookup_list_items)) {
                                continue;
                            }
                            $lookup_list_collection = new Collection($list_entity->lookup_list_items);
                            $lookup_list_entity = $lookup_list_collection
                                ->firstMatch(['item_id' => $entity->{$list_name}]);
                            $entity->{$slug_field} = $lookup_list_entity->slug;
                            $entity->{$value_field} = $lookup_list_entity->value;
                        }
                        return $entity;
                   });
            });

        return $query;
    }

    public function beforeSave(Event $event, Entity $entity)
    {
        $fields = Hash::extract($this->_config, "fields.{s}.slug_field");

        foreach ($fields as $field) {
            if ($entity->{$field} !== $entity->getOriginal($field)) {
                $value = $entity->{$field};
                $db_field_name = $this->_extractFieldFromSlugName($field);
                $db_id = $this->getLookupListItemId($db_field_name, $value);
                $entity->{$db_field_name} = $db_id;
                unset($entity->{$field});
            }
        }
        return true;
    }

    public function getLookupListItemId($field, $item_slug)
    {
        $list_slug = null;

        if (isset($this->_config['fields'][$field]['list'])) {
            $list_slug = $this->_config['fields'][$field]['list'];
        }

        if ($list_slug) {
            return $this->_LookupLists->getItemId($list_slug, $item_slug);
        }

        return null;
    }

    public function getLookupListDefault($field)
    {
        $list_slug = null;

        if (isset($this->_config['fields'][$field]['list'])) {
            $list_slug = $this->_config['fields'][$field]['list'];
        }

        if ($list_slug) {
            return $this->_LookupLists->getDefault($list_slug);
        }

        return null;
    }

    protected function _extractFieldFromSlugName($slug)
    {
        foreach ($this->_config['fields'] as $key => $field) {
            if (!isset($field['slug_field'])) {
                continue;
            }

            if (strtolower($slug) == strtolower($field['slug_field'])) {
                return $key;
            }
        }

        return null;
    }

    private function _extract_list_name_from_slug_name(\Model $model, $slug)
    {
        foreach ($this->settings[$model->name]['fields'] as $key => $field) {
            if (!isset($field['list'])) {
                continue;
            }

            if (strtolower($slug) == strtolower($field['slug_field'])) {
                return $field['list'];
            }
        }

        return null;
    }

}
