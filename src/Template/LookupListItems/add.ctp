<ol class="breadcrumb">
    <li><a href="/">Home</a></li>
    <li><?php echo $this->Html->link('Lists', ['controller' => 'LookupLists', 'action' => 'index']) ?></li>
    <li><?php echo $this->Html->link('Edit ' . $lookupList->name, ['controller' => 'LookupLists', 'action' => 'edit', $lookupList->id]) ?></li>
    <li class="active">Add List Item</li>
</ol>

<div class="lookupListItems form">
    <?php echo $this->Form->create($lookupListItem); ?>
    <fieldset>
        <legend><?php echo __('Add Lookup List Item'); ?></legend>
        <?php
        echo $this->Form->input('lookup_list_id', ['type' => 'hidden', 'value' => $lookupList->id]);
        echo $this->Form->input('value', [
            'div' => ['class' => 'form-group'],
            'label' => [],
            'class' => 'form-control'
        ]);
        //echo $this->Form->input('display_order');
        echo $this->Form->input('default');
        echo $this->Form->input('public');
        ?>
    </fieldset>
    <?php echo $this->Form->submit(__('Submit'), ['class' => 'btn btn-primary']); ?>
    <?php echo $this->Form->end();?>
</div>

