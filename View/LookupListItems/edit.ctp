<div class="lookupListItems form">
    <?php echo $this->Form->create('LookupListItem'); ?>
    <fieldset>
        <legend><?php echo __('Edit Lookup List Item'); ?></legend>
        <?php
        echo $this->Form->input('id');
        echo $this->Form->input('lookup_list_id', array('type' => 'hidden'));
        echo $this->Form->input('item_id', array('type' => 'text','div' => array('class' => 'form-group'),
            'label' => array(),
            'class' => 'form-control'));
        echo $this->Form->input('slug', array(
            'div' => array('class' => 'form-group'),
            'label' => array(),
            'class' => 'form-control'
        ));
        echo $this->Form->input('value', array(
            'div' => array('class' => 'form-group'),
            'label' => array(),
            'class' => 'form-control'
        ));
        echo $this->Form->input('display_order', array(
            'div' => array('class' => 'form-group'),
            'label' => array(),
            'class' => 'form-control'
        ));
        echo $this->Form->input('default', array('div' => array('class' => 'checkbox'), 'label' => false, 'before' => '<label>', 'after' => 'Default</label>',));
        echo $this->Form->input('public', array('div' => array('class' => 'checkbox'), 'label' => false, 'before' => '<label>', 'after' => 'Public</label>',));
        ?>
    </fieldset>
    <?php echo $this->Form->submit(__('Submit'), array('class' => 'btn btn-primary')); ?>
</div>
