<?php

namespace LookupLists\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Crud\Controller\ControllerTrait;

/**
 * LookupLists Controller
 *
 * @property LookupList $LookupList
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class LookupListsController extends AppController
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
                'Crud.Index',
                'Crud.Add',
                'Crud.Edit',
                'Crud.Delete'
            ]
        ]
    ];

    public function edit()
    {
        $this->Crud->on(
            'Crud.beforeFind',
            function (Event $event) {
                $event->subject->query
                    ->contain([
                        'LookupListItems'
                    ]);
            }
        );

        return $this->Crud->execute();
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
