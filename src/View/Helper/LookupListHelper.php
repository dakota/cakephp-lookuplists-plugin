<?php

namespace LookupLists\View\Helper;

use Cake\ORM\TableRegistry;
use Cake\View\Helper;

class LookupListHelper extends Helper
{

    public $helpers = ['Form'];

    public function makeList($field, $list_slug, $options = [])
    {
        $lookup_lists = TableRegistry::get('LookupLists.LookupLists');

        $list = $lookup_lists->find('items', ['list_slug' => $list_slug]);
        $default = $lookup_lists->getDefault($list_slug);
        
        $field_options = array_merge(['options' => $list, 'default' => $default], $options);
        //debug($field_options);exit;
        
        return $this->Form->input($field, $field_options);
    }

}
