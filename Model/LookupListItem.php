<?php

App::uses('LookupListsAppModel', 'LookupLists.Model');

/**
 * LookupListItem Model
 *
 * @property LookupList $LookupList
 */
class LookupListItem extends LookupListsAppModel
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
    public $validate = array(
        'lookup_list_id' => array(
            'numeric' => array(
                'rule' => array('numeric'),
            ),
        ),
        'item_id' => array(
            'numeric' => array(
                'rule' => array('numeric'),
            ),
        ),
        'slug' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
            'uniquePerList' => array(
                'rule' => array('uniquePerList'),
                'message' => 'List slug must be unique',
                'on' => 'create',
            ),
        ),
        'value' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
            ),
        ),
        'display_order' => array(
            'numeric' => array(
                'rule' => array('numeric'),
            ),
        ),
        'default' => array(
            'boolean' => array(
                'rule' => array('boolean'),
            ),
        ),
        'public' => array(
            'boolean' => array(
                'rule' => array('boolean'),
            ),
        ),
    );

    /**
     * belongsTo associations
     *
     * @var array
     */
    public $belongsTo = array(
        'LookupList' => array(
            'className' => 'LookupList',
            'foreignKey' => 'lookup_list_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );

    public function beforeSave($options = array())
    {
        parent::beforeSave($options);

        $list_values = $this->find('first', array(
            'conditions' => array('LookupListItem.lookup_list_id' => $this->data["LookupListItem"]['lookup_list_id']),
            'fields' => array('max(item_id)+1 as new_item_id', 'max(display_order)+1 as new_display_order'),
        ));

        if (!isset($this->data["LookupListItem"]['slug']))
        {
            $this->data["LookupListItem"]['slug'] = Inflector::slug($this->data["LookupListItem"]['value']);
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
                $this->updateAll(array("LookupListItem.default" => false), array('LookupListItem.lookup_list_id' => $this->data["LookupListItem"]['lookup_list_id']));
            }
        }

        return $this->validates();
    }

    public function afterSave($created, $options = array())
    {
        parent::afterSave($created, $options);

        if (isset($this->data["LookupListItem"]["lookup_list_id"]))
        {
            $keys = array(
                'LookupListDefaultByListID_' . $this->data["LookupListItem"]["lookup_list_id"],
                'LookupListItemsByListID_' . $this->data["LookupListItem"]["lookup_list_id"],
            );

            foreach ($keys as $key)
                Cache::delete($key);
        }
    }

    public function uniquePerList($conditions)
    {
        if (isset($this->data['LookupListItem']['lookup_list_id']))
        {
            $find = $this->find('count', array('conditions' => array(
                    'LookupListItem.lookup_list_id' => $this->data['LookupListItem']['lookup_list_id'],
                    'LookupListItem.slug' => $conditions['slug'],
            )));

            if ($find > 0)
                return false;
        }

        return true;
    }

}
