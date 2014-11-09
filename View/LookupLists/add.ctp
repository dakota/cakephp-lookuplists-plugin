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
        echo $this->Form->input('public', array('default' => 1, 'div' => array('class' => 'checkbox'),'label' => false,'before' => '<label>','after' => 'Public</label>',));
        ?>
    </fieldset>
    <?php echo $this->Form->submit(__('Submit'), array('class' => 'btn btn-primary')); ?>
</div>
