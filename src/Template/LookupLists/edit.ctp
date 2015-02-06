<ol class="breadcrumb">
    <li><a href="/">Home</a></li>
    <li><?php echo $this->Html->link('Lists', array('action' => 'index')) ?></li>
    <li class="active">Edit List</li>
</ol>

<div class="related">
    <h4><?php echo __('Related Lookup List Items'); ?></h4>
    <p>
        <?php echo $this->Html->link('New List Item', array('controller' => 'lookup_list_items', 'action' => 'add', '?' => array('lookup_list_id' => $lookupList["LookupList"]["id"])), array('class' => 'btn btn-primary btn-xs')) ?>
    </p>
    <?php if (!empty($lookupList['LookupListItem'])): ?>
        <table class="table">
            <tr>
                <th><?php echo __('Item Id'); ?></th>
                <th><?php echo __('Value'); ?></th>
                <th><?php echo __('Slug'); ?></th>
                <th><?php echo __('Display Order'); ?></th>
                <th><?php echo __('Default'); ?></th>
                <th><?php echo __('Public'); ?></th>
                <th class="actions" style="width:100px"><?php echo __('Actions'); ?></th>
            </tr>
            <?php foreach ($lookupList['LookupListItem'] as $lookupListItem): ?>
                <tr>
                    <td><?php echo $lookupListItem['item_id']; ?></td>
                    <td><?php echo $lookupListItem['value']; ?></td>
                    <td><?php echo $lookupListItem['slug']; ?></td>
                    <td><?php echo $lookupListItem['display_order']; ?></td>
                    <td><?php echo $lookupListItem['default'] ? 'Yes' : 'No'; ?></td>
                    <td><?php echo $lookupListItem['public'] ? 'Yes' : 'No'; ?></td>
                    <td class="actions">
                        <?php echo $this->Html->link(__('Edit'), array('controller' => 'lookup_list_items', 'action' => 'edit', $lookupListItem['id'], '?' => array('lookup_list_id' => $lookupList["LookupList"]["id"])), array('class' => 'btn btn-primary btn-xs')); ?>
                        <?php echo $this->Form->postLink(__('Delete'), array('controller' => 'lookup_list_items', 'action' => 'delete', $lookupListItem['id']), array('class' => 'btn btn-danger btn-xs'), __('Are you sure you want to delete # {0}?', $lookupListItem['id'])); ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</div>

<div class="lookupLists form">
    <?php echo $this->Form->create('LookupList'); ?>
    <fieldset>
        <legend><?php echo __('Edit Lookup List'); ?></legend>
        <?php
        echo $this->Form->input('id');
        echo $this->Form->input('slug', array(
            'div' => array('class' => 'form-group'),
            'label' => array(),
            'class' => 'form-control'
        ));
        echo $this->Form->input('name', array(
            'div' => array('class' => 'form-group'),
            'label' => array(),
            'class' => 'form-control'
        ));
        echo $this->Form->input('public', array('div' => array('class' => 'checkbox'), 'label' => false, 'before' => '<label>', 'after' => 'Public</label>',));
        ?>
    </fieldset>
    <?php echo $this->Form->submit(__('Submit'), array('class' => 'btn btn-primary')); ?>
</div>



