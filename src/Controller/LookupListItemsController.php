<?php

namespace LookupLists\Controller;

use App\Controller\AppController;
use Cake\Datasource\ConnectionManager;
use Cake\Event\Event;
use Crud\Controller\ControllerTrait;

/**
 * LookupListItems Controller
 *
 * @property LookupListItem $LookupListItem
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class LookupListItemsController extends AppController
{

    use ControllerTrait;

    /**
     * Components
     *
     * @var array
     */
    public $components = [
        'Crud.Crud' => [
            'actions' => [
                'Crud.Add',
                'Crud.Edit',
                'Crud.Delete'
            ]
        ]
    ];

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);

        $this->Crud->on(
            'Crud.beforeRedirect',
            function (Event $event) {
                $event->subject->url = [
                    'controller' => 'LookupLists',
                    'action' => 'edit',
                    $this->request->query["lookup_list_id"]
                ];
            }
        );
    }

    /**
     * add method
     *
     * @return void
     */
    public function add()
    {
        if (!isset($this->request->query["lookup_list_id"])) {
            return $this->redirect(['controller' => 'lookup_lists', 'action' => 'index']);
        }

        $lookupList = $this->LookupListItems->LookupLists->get($this->request->query["lookup_list_id"]);

        $this->set(compact('lookupList'));

        ConnectionManager::get('default')->driver()->autoQuoting(true);
        return $this->Crud->execute();
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
        ConnectionManager::get('default')->driver()->autoQuoting(true);
        $lookupList = $this->LookupListItems->LookupLists->get($this->request->query["lookup_list_id"]);
        $this->set(compact('lookupList'));

        return $this->Crud->execute();
    }

    public function delete()
    {
        return $this->Crud->execute();
    }
}
