<div class="lookupListItems form">
    <?php echo $this->Form->create('LookupListItem'); ?>
    <fieldset>
        <legend><?php echo __('Add Lookup List Item'); ?></legend>
        <?php
        echo $this->Form->input('lookup_list_id');
        echo $this->Form->input('value');
        //echo $this->Form->input('display_order');
        echo $this->Form->input('default');
        echo $this->Form->input('public');
        ?>
    </fieldset>
    <?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
    <h3><?php echo __('Actions'); ?></h3>
    <ul>

        <li><?php echo $this->Html->link(__('List Lookup List Items'), array('action' => 'index')); ?></li>
        <li><?php echo $this->Html->link(__('List Lookup Lists'), array('controller' => 'lookup_lists', 'action' => 'index')); ?> </li>
        <li><?php echo $this->Html->link(__('New Lookup List'), array('controller' => 'lookup_lists', 'action' => 'add')); ?> </li>
    </ul>
</div>
