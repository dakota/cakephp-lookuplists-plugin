<div class="lookupListItems form">
    <?php echo $this->Form->create($lookupListItem); ?>
    <fieldset>
        <legend><?php echo __('Edit Lookup List Item'); ?></legend>
        <?php
        echo $this->Form->input('id');
        echo $this->Form->input('lookup_list_id', ['type' => 'hidden']);
        echo $this->Form->input('item_id', ['type' => 'text','div' => ['class' => 'form-group'],
            'label' => [],
            'class' => 'form-control']);
        echo $this->Form->input('slug', [
            'div' => ['class' => 'form-group'],
            'label' => [],
            'class' => 'form-control'
        ]);
        echo $this->Form->input('value', [
            'div' => ['class' => 'form-group'],
            'label' => [],
            'class' => 'form-control'
        ]);
        echo $this->Form->input('display_order', [
            'div' => ['class' => 'form-group'],
            'label' => [],
            'class' => 'form-control'
        ]);
        echo $this->Form->input('default');
        echo $this->Form->input('public');
        ?>
    </fieldset>
    <?php echo $this->Form->submit(__('Submit'), ['class' => 'btn btn-primary']); ?>
    <?php echo $this->Form->end(); ?>
</div>
