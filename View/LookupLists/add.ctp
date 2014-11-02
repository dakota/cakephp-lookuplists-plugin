<div class="lookupLists form">
    <h3>Create new Lookup List</h3>
    <?php echo $this->Form->create('LookupList', array()); ?>
    <fieldset>
        <?php
        echo $this->Form->input('name', array(
            'div' => array('class' => 'form-group'),
            'label' => array(),
            'class' => 'form-control'
        ));
        echo $this->Form->input('public', array('div' => array('class' => 'checkbox'),'label' => false,'before' => '<label>','after' => 'Public</label>',));
        ?>
    </fieldset>
    <?php echo $this->Form->submit(__('Submit'), array('class' => 'btn btn-primary')); ?>
</div>
<div class="actions">
    <h3><?php echo __('Actions'); ?></h3>
    <ul>

        <li><?php echo $this->Html->link(__('List Lookup Lists'), array('action' => 'index')); ?></li>
        <li><?php echo $this->Html->link(__('List Lookup List Items'), array('controller' => 'lookup_list_items', 'action' => 'index')); ?> </li>
        <li><?php echo $this->Html->link(__('New Lookup List Item'), array('controller' => 'lookup_list_items', 'action' => 'add')); ?> </li>
    </ul>
</div>
