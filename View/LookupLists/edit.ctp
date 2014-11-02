<div class="lookupLists form">
    <?php echo $this->Form->create('LookupList'); ?>
    <fieldset>
        <legend><?php echo __('Edit Lookup List'); ?></legend>
        <?php
        echo $this->Form->input('id');
        echo $this->Form->input('slug');
        echo $this->Form->input('name');
        echo $this->Form->input('public');
        ?>
    </fieldset>
    <?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
    <h3><?php echo __('Actions'); ?></h3>
    <ul>

        <li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('LookupList.id')), array(), __('Are you sure you want to delete # %s?', $this->Form->value('LookupList.id'))); ?></li>
        <li><?php echo $this->Html->link(__('List Lookup Lists'), array('action' => 'index')); ?></li>
        <li><?php echo $this->Html->link(__('List Lookup List Items'), array('controller' => 'lookup_list_items', 'action' => 'index')); ?> </li>
        <li><?php echo $this->Html->link(__('New Lookup List Item'), array('controller' => 'lookup_list_items', 'action' => 'add')); ?> </li>
    </ul>
</div>
