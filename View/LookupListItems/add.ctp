<ol class="breadcrumb">
    <li><a href="/">Home</a></li>
    <li><?php echo $this->Html->link('Lists', array('action' => 'index')) ?></li>
    <li><?php echo $this->Html->link('Edit List', array('controller' => 'lookup_lists','action' => 'edit', $lookupList['LookupList']['id'])) ?></li>
    <li class="active">Add List Item</li>
</ol>

<div class="lookupListItems form">
    <?php echo $this->Form->create('LookupListItem'); ?>
    <fieldset>
        <legend><?php echo __('Add Lookup List Item'); ?></legend>
        <?php
        echo $this->Form->input('lookup_list_id', array('type' => 'hidden', 'value' => $lookupList['LookupList']['id']));
        echo $this->Form->input('value', array(
            'div' => array('class' => 'form-group'),
            'label' => array(),
            'class' => 'form-control'
        ));
        //echo $this->Form->input('display_order');
        echo $this->Form->input('default', array('div' => array('class' => 'checkbox'), 'label' => false, 'before' => '<label>', 'after' => 'Default</label>',));
        echo $this->Form->input('public', array('div' => array('class' => 'checkbox'), 'label' => false, 'before' => '<label>', 'after' => 'Public</label>',));
        ?>
    </fieldset>
    <?php echo $this->Form->submit(__('Submit'), array('class' => 'btn btn-primary')); ?>
</div>

