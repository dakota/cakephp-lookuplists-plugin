<?php

namespace Controller;

use LookupLists\Controller\LookupListsAppController;

/**
 * LookupListItems Controller
 *
 * @property LookupListItem $LookupListItem
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class LookupListItemsController extends LookupListsAppController
{

    /**
     * Components
     *
     * @var array
     */
    public $components = array('Paginator', 'Session');
    public $uses = array('LookupLists.LookupList', 'LookupLists.LookupListItem');

    /**
     * add method
     *
     * @return void
     */
    public function add()
    {


        if ($this->request->is('post'))
        {
            $this->LookupListItem->create();
            if ($this->LookupListItem->save($this->request->data))
            {
                $this->Session->setFlash(__('The lookup list item has been saved.'), 'flash_notification');
                return $this->redirect(array('controller' => 'lookup_lists', 'action' => 'edit', $this->request->data["LookupListItem"]['lookup_list_id']));
            }
            else
            {
                $this->Session->setFlash(__('The lookup list item could not be saved. Please, try again.'), 'flash_error');
            }
        }


        if (!isset($this->request->query["lookup_list_id"]))
        {
            return $this->redirect(array('controller' => 'lookup_lists', 'action' => 'index'));
        }

        if (!$this->LookupList->exists($this->request->query["lookup_list_id"]))
        {
            throw new NotFoundException(__('Invalid lookup list'));
        }

        $this->LookupListItem->recursive = -1;
        $lookupList = $this->LookupList->find('first', array('conditions' => array('LookupList.id' => $this->request->query["lookup_list_id"])));

        //debug($lookupList);
        //$lookupLists = $this->LookupListItem->LookupList->find('list');
        $this->set(compact('lookupList'));
    }

    /**
     * edit method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function edit($id = null)
    {
        if (!$this->LookupListItem->exists($id))
        {
            throw new NotFoundException(__('Invalid lookup list item'));
        }
        if ($this->request->is(array('post', 'put')))
        {
            if ($this->LookupListItem->save($this->request->data))
            {
                $this->Session->setFlash(__('The lookup list item has been saved.'), 'flash_notification');
                return $this->redirect(array('controller' => 'lookup_lists', 'action' => 'edit', $this->request->data["LookupListItem"]['lookup_list_id']));
            }
            else
            {
                debug($this->LookupListItem->validationErrors);
                $this->Session->setFlash(__('The lookup list item could not be saved. Please, try again.'), 'flash_error');
            }
        }
        else
        {
            $options = array('conditions' => array('LookupListItem.' . $this->LookupListItem->primaryKey => $id));
            $this->request->data = $this->LookupListItem->find('first', $options);
        }
        $lookupLists = $this->LookupListItem->LookupList->find('list');
        $this->set(compact('lookupLists'));
    }

    /**
     * delete method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function delete($id = null)
    {
        $lookup_list = $this->LookupListItem->find('first', array('conditions' => array('LookupListItem.id' => $id)));

        $list_id = null;

        if ($lookup_list)
            $list_id = $lookup_list["LookupListItem"]["lookup_list_id"];

        $this->LookupListItem->id = $id;
        if (!$this->LookupListItem->exists())
        {
            throw new NotFoundException(__('Invalid lookup list item'));
        }
        $this->request->allowMethod('post', 'delete');
        if ($this->LookupListItem->delete())
        {
            $this->Session->setFlash(__('The lookup list item has been deleted.'), 'flash_notification');
        }
        else
        {
            $this->Session->setFlash(__('The lookup list item could not be deleted. Please, try again.'), 'flash_error');
        }




        return $this->redirect(array('controller' => 'lookup_lists', 'action' => 'edit', $list_id));
    }

}
