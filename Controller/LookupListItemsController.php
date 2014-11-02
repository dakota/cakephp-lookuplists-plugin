<?php
App::uses('LookupListsAppController', 'LookupLists.Controller');
/**
 * LookupListItems Controller
 *
 * @property LookupListItem $LookupListItem
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class LookupListItemsController extends LookupListsAppController {

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
	public function index() {
		$this->LookupListItem->recursive = 0;
		$this->set('lookupListItems', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->LookupListItem->exists($id)) {
			throw new NotFoundException(__('Invalid lookup list item'));
		}
		$options = array('conditions' => array('LookupListItem.' . $this->LookupListItem->primaryKey => $id));
		$this->set('lookupListItem', $this->LookupListItem->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->LookupListItem->create();
			if ($this->LookupListItem->save($this->request->data)) {
				$this->Session->setFlash(__('The lookup list item has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The lookup list item could not be saved. Please, try again.'));
			}
		}
		$lookupLists = $this->LookupListItem->LookupList->find('list');
		$this->set(compact('lookupLists'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->LookupListItem->exists($id)) {
			throw new NotFoundException(__('Invalid lookup list item'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->LookupListItem->save($this->request->data)) {
				$this->Session->setFlash(__('The lookup list item has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The lookup list item could not be saved. Please, try again.'));
			}
		} else {
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
	public function delete($id = null) {
		$this->LookupListItem->id = $id;
		if (!$this->LookupListItem->exists()) {
			throw new NotFoundException(__('Invalid lookup list item'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->LookupListItem->delete()) {
			$this->Session->setFlash(__('The lookup list item has been deleted.'));
		} else {
			$this->Session->setFlash(__('The lookup list item could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
