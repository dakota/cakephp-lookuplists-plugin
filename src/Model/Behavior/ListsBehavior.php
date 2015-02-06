<?php

namespace Model\Behavior;

use Cake\Cache\Cache;
use Cake\Model\Behavior;
use Cake\Model\Model;

class ListsBehavior extends ModelBehavior
{

    public $settings;

    public function setup(\Model $model, $config = array())
    {
        parent::setup($model, $config);

        $this->settings[$model->name] = array_merge(array('show_display_field' => true), $config);
    }

    public function afterFind(\Model $model, $results, $primary = false)
    {

        parent::afterFind($model, $results, $primary);

        $this->LookupList = ClassRegistry::init('LookupLists.LookupList');
        $list_data = array();

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

                    $list_data[$field] = $this->LookupList->find('first', array('recursive' => 1, 'conditions' => array('LookupList.slug' => $list_name)));
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

    public function beforeSave(\Model $model, $options = array())
    {
        parent::beforeSave($model, $options);

        $fields = Hash::extract($this->settings[$model->name], "fields.{s}.slug_field");

        foreach ($fields as $field)
        {
            if (isset($model->data[$model->name][$field]))
            {
                $db_field_name = $this->_extract_field_from_slug_name($model, $field);
                $value = $model->data[$model->name][$field];
                $db_id = $this->getLookupListItemId($model, $db_field_name, $value);
                $model->data[$model->name][$db_field_name] = $db_id;
                unset($model->data[$model->name][$field]);
            }
        }
        return true;
    }

    public function getLookupListItemId(\Model $model, $field, $item_slug)
    {
        $this->LookupList = ClassRegistry::init('LookupLists.LookupList');

        $list_slug = null;

        if (isset($this->settings[$model->name]['fields'][$field]['list']))
        {
            $list_slug = $this->settings[$model->name]['fields'][$field]['list'];
        }

        if ($list_slug)
        {
            return $this->LookupList->getItemId($list_slug, $item_slug);
        }

        return null;
    }

    public function getLookupListDefault(\Model $model, $field)
    {
        $this->LookupList = ClassRegistry::init('LookupLists.LookupList');

        $list_slug = null;
        
        if (isset($this->settings[$model->name]['fields'][$field]['list']))
        {
            $list_slug = $this->settings[$model->name]['fields'][$field]['list'];
        }

        if ($list_slug)
        {
            return $this->LookupList->getDefault($list_slug);
        }

        return null;
    }

    private function _extract_field_from_slug_name(\Model $model, $slug)
    {
        foreach ($this->settings[$model->name]['fields'] as $key => $field)
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
