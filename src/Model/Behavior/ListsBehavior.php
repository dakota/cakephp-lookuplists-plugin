<?php

namespace LookupLists\Model\Behavior;

use Cake\Cache\Cache;
use Cake\Collection\Collection;
use Cake\Datasource\EntityInterface;
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

    protected function _loadListData($alias, $fields)
    {
        $list_data = [];

        foreach ($fields as $field => $db_list_name) {
            $key = "LookupListData." . $alias . "." . $field;

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
        return $list_data;
    }
    
    public function beforeFind(Event $event, Query $query)
    {
        $fields = $this->config('fields');

        if (empty($fields)) {
            return $query;
        }

        $list_data = $this->_loadListData($event->subject()->alias(), $fields);

        if (empty($list_data)) {
            return $query;
        }

        $query
            ->formatResults(function ($results) use ($list_data, $fields) {
               return $results
                   ->map(function ($entity) use ($list_data, $fields) {
                       if ($entity instanceof Entity) {
                           return $this->formatEntity($entity, $list_data);
                       }
                       return $entity;
                   });
            });

        return $query;
    }

    public function formatEntity(Entity $entity, $list_data = null)
    {
        $fields = $this->config('fields');
        if (!$list_data) {
            $source = $entity->source();
            $list_data = $this->_loadListData($source, $fields);
        }

        $properties = $entity->visibleProperties();

        foreach ($properties as $property) {
            if (empty($entity->{$property})) {
                continue;
            }

            if (isset($list_data[$property])) {
                $slug_field = "{$property}_slug";
                $value_field = "{$property}_value";

                if (isset($fields[$property]['slug_field'])) {
                    $slug_field = $fields[$property]['slug_field'];
                }

                if (isset($fields[$property]['value_field'])) {
                    $value_field = $fields[$property]['value_field'];
                }

                $entity->{$slug_field} = null;
                $entity->{$value_field} = null;

                if (empty($list_data[$property]->lookup_list_items)) {
                    continue;
                }
                $lookup_list_collection = new Collection($list_data[$property]->lookup_list_items);
                $lookup_list_entity = $lookup_list_collection->firstMatch(['item_id' => $entity->{$property}]);
                $entity->{$slug_field} = $lookup_list_entity->slug;
                $entity->{$value_field} = $lookup_list_entity->value;
            }

            if ($entity->{$property} instanceof EntityInterface) {
                $entity_source = $this->_table->association($entity->{$property}->source());
                if ($entity_source->hasBehavior('Lists')) {
                    $entity_source->formatEntity($entity->{$property});
                }
            }
        }

        return $entity;
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

}
