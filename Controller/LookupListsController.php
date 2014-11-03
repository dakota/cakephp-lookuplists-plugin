<?php

App::uses('LookupListsAppController', 'LookupLists.Controller');

/**
 * LookupLists Controller
 *
 * @property LookupList $LookupList
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class LookupListsController extends LookupListsAppController
{

    /**
     * Components
     *
     * @var array
     */
    public $components = array('Paginator', 'Session');

    /**
     * index method
     *
     * @return void
     */
    public function index()
    {
        $this->LookupList->recursive = 0;
        $this->set('lookupLists', $this->Paginator->paginate());
    }

    /**
     * add method
     *
     * @return void
     */
    public function add()
    {
        if ($this->request->is('post'))
        {
            $this->LookupList->create();
            if ($this->LookupList->save($this->request->data))
            {
                $this->Session->setFlash(__('The lookup list has been saved.'), 'flash_notification');
                return $this->redirect(array('action' => 'index'));
            }
            else
            {
                $this->Session->setFlash(__('The lookup list could not be saved. Please, try again.'));
            }
        }
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
        if (!$this->LookupList->exists($id))
        {
            throw new NotFoundException(__('Invalid lookup list'));
        }
        if ($this->request->is(array('post', 'put')))
        {
            if ($this->LookupList->save($this->request->data))
            {
                $this->Session->setFlash(__('The lookup list has been saved.'), 'flash_notification');
                return $this->redirect(array('action' => 'edit', $id));
            }
            else
            {
                $this->Session->setFlash(__('The lookup list could not be saved. Please, try again.'), 'flash_error');
            }
        }
        else
        {
            $options = array('conditions' => array('LookupList.' . $this->LookupList->primaryKey => $id));
            $this->request->data = $this->LookupList->find('first', $options);
        }
        $options = array('conditions' => array('LookupList.' . $this->LookupList->primaryKey => $id));
        $this->set('lookupList', $this->LookupList->find('first', $options));
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
        $this->LookupList->id = $id;
        if (!$this->LookupList->exists())
        {
            throw new NotFoundException(__('Invalid lookup list'), 'flash_error');
        }
        $this->request->allowMethod('post', 'delete');
        if ($this->LookupList->delete($id, true))
        {
            $this->Session->setFlash(__('The lookup list has been deleted.'), 'flash_notification');
        }
        else
        {
            $this->Session->setFlash(__('The lookup list could not be deleted. Please, try again.'), 'flash_error');
        }
        return $this->redirect(array('action' => 'index'));
    }

    //## Download a json file with all available list data
    public function export()
    {
        $data = $this->LookupList->find('all');
        $data = json_encode($data);
        echo($data);
        exit();
    }

    public function import()
    {
        
    }

}
