<?php
/**
 * Created by PhpStorm.
 * User: waltherlalk
 * Date: 15/03/03
 * Time: 3:42 PM
 */

namespace LookupLists\Model\Entity;


use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;

class LookupListItem extends Entity
{

    protected function _getLookupList($lookupList)
    {
        if (!$lookupList && $this->lookup_list_id) {
            $lookupListTable = TableRegistry::get('LookupLists.LookupLists');
            $lookupList = $lookupListTable->get($this->lookup_list_id);

            $this->lookup_list = $lookupList;
        }

        return $lookupList;
    }

}