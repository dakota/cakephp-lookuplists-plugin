<?php

namespace LookupLists\Model\Behavior;

use Cake\Cache\Cache;
use Cake\Event\Event;
use Cake\ORM\Behavior;
use Cake\ORM\Entity;
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
    protected $_LookupLists ;

    public function initialize(array $config)
    {
        $this->_LookupLists = TableRegistry::get('LookupLists.LookupLists');
    }

    public function afterFind(\Model $model, $results, $primary = false)
    {

        parent::afterFind($model, $results, $primary);

        $this->LookupList = ClassRegistry::init('LookupLists.LookupList');
        $list_data = [];

        //debug($this->settings[$model->name]);

        if (isset($this->settings[$model->name]["fields"]))
        {
            foreach ($this->settings[$model->name]["fields"] as $field => $db_list_name)
            {
                //debug($db_list_name);
                $key = "LookupListData_" . $model->name . "_" . $field;
                //debug($key);

                $list_name = null;

                if (is_array($db_list_name))
                {

                    if (isset($db_list_name['list']))
                    {

                        $list_name = $db_list_name['list'];
                    }
                }

                if (is_null($list_name))
                {

                    $list_name = $db_list_name;
                }


                if (!$list_data[$field] = Cache::read($key))
                {

                    $list_data[$field] = $this->LookupList->find('first', ['recursive' => 1, 'conditions' => ['LookupList.slug' => $list_name]]);
                    Cache::write($key, $list_data[$field]);
                }
            }
        }


        if (!$list_data)
        {
            return $results;
        }


        foreach ($results as $result_key => $result)
        {
            if (!isset($result[$model->name]))
            {
                continue;
            }

            foreach ($result[$model->name] as $key => $value)
            {
                //debug($list_data);

                foreach ($list_data as $list_name => $list_array)
                {
                    if ($list_name == $key)
                    {
                        $slug_field = "{$key}_slug";
                        $value_field = "{$key}_value";

                        if (isset($this->settings[$model->name]['fields'][$key]['slug_field']))
                        {
                            $slug_field = $this->settings[$model->name]['fields'][$key]['slug_field'];
                        }

                        if (isset($this->settings[$model->name]['fields'][$key]['value_field']))
                        {
                            $value_field = $this->settings[$model->name]['fields'][$key]['value_field'];
                        }


                        $results[$result_key][$model->name][$slug_field] = null;
                        $results[$result_key][$model->name][$value_field] = null;
                        if (isset($list_array["LookupListItem"]))
                        {
                            foreach ($list_array["LookupListItem"] as $list_item)
                            {
                                if ($value == $list_item["item_id"])
                                {
                                    $results[$result_key][$model->name][$slug_field] = $list_item["slug"];
                                    $results[$result_key][$model->name][$value_field] = $list_item["value"];
                                }
                            }
                        }
                    }
                }
            }
        }

        //exit();

        return $results;
    }

    public function beforeSave(Event $event, Entity $entity)
    {
        $fields = Hash::extract($this->_config, "fields.{s}.slug_field");

        foreach ($fields as $field)
        {
            $value = $entity->{$field};
            if (isset($value))
            {
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

        if (isset($this->_config['fields'][$field]['list']))
        {
            $list_slug = $this->_config['fields'][$field]['list'];
        }

        if ($list_slug)
        {
            return $this->_LookupLists->getItemId($list_slug, $item_slug);
        }

        return null;
    }

    public function getLookupListDefault($field)
    {
        $list_slug = null;
        
        if (isset($this->_config['fields'][$field]['list']))
        {
            $list_slug = $this->_config['fields'][$field]['list'];
        }

        if ($list_slug)
        {
            return $this->_LookupLists->getDefault($list_slug);
        }

        return null;
    }

    protected function _extractFieldFromSlugName($slug)
    {
        foreach ($this->_config['fields'] as $key => $field)
        {
            if (!isset($field['slug_field']))
            {
                continue;
            }

            if (strtolower($slug) == strtolower($field['slug_field']))
            {
                return $key;
            }
        }

        return null;
    }

    private function _extract_list_name_from_slug_name(\Model $model, $slug)
    {
        foreach ($this->settings[$model->name]['fields'] as $key => $field)
        {
            if (!isset($field['list']))
            {
                continue;
            }

            if (strtolower($slug) == strtolower($field['slug_field']))
            {
                return $field['list'];
            }
        }

        return null;
    }

}
