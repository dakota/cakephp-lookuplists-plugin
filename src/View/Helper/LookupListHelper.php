<?php

namespace LookupLists\View\Helper;

use Cake\View\Helper;

class LookupListHelper extends Helper
{

    public $helpers = ['Html', 'Form'];

    public function makeList($field, $list_slug, $options = [])
    {
        $this->LookupList = ClassRegistry::init('LookupLists.LookupList');

        $list = $this->LookupList->listItems($list_slug);
        $default = $this->LookupList->getDefault($list_slug);
        
        $field_options = array_merge(['options' => $list, 'default' => $default], $options);
        //debug($field_options);exit;
        
        return $this->Form->input($field, $field_options);
    }

}
