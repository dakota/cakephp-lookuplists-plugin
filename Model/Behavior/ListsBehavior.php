<?php

class ListsBehavior extends ModelBehavior
{

    public $settings;

    public function setup(\Model $model, $config = array())
    {
        parent::setup($model, $config);

        $this->settings = array_merge(array('show_display_field' => true), $config);
    }

    public function afterFind(\Model $model, $results, $primary = false)
    {

        parent::afterFind($model, $results, $primary);

        $this->LookupList = ClassRegistry::init('LookupLists.LookupList');
        $list_data = array();

        if (isset($this->settings["fields"]))
        {
            foreach ($this->settings["fields"] as $field => $db_list_name)
            {

                $key = "LookupListData_" . $model->name . "_" . $field;

                if (!$list_data[$field] = Cache::read($key))
                {
                    $list_data[$field] = $this->LookupList->find('first', array('conditions' => array('LookupList.slug' => $db_list_name)));
                    Cache::write($key, $list_data[$field]);
                }

                
            }
        }

        if ($list_data)
        {
            foreach ($results as $result_key => $result)
            {
                if (isset($result[$model->name]))
                {
                    foreach ($result[$model->name] as $key => $value)
                    {
                        foreach ($list_data as $list_name => $list_array)
                        {
                            if ($list_name == $key)
                            {
                                $results[$result_key][$model->name]["{$key}_slug"] = null;
                                $results[$result_key][$model->name]["{$key}_value"] = null;
                                if (isset($list_array["LookupListItem"]))
                                {
                                    foreach ($list_array["LookupListItem"] as $list_item)
                                    {
                                        if ($value == $list_item["item_id"])
                                        {
                                            $results[$result_key][$model->name]["{$key}_slug"] = $list_item["slug"];
                                            $results[$result_key][$model->name]["{$key}_value"] = $list_item["value"];
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        return $results;
    }

    public function getLookupListItemId(\Model $model, $field, $item_slug)
    {
        $this->LookupList = ClassRegistry::init('LookupLists.LookupList');

        $list_slug = null;

        if (isset($this->settings['fields'][$field]))
        {
            $list_slug = $this->settings['fields'][$field];
        }

        if ($list_slug)
        {
            return $this->LookupList->getItemId($list_slug, $item_slug);
        }

        return null;
    }

}
