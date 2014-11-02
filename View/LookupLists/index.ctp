<div class="lookupLists index">
    <h2><?php echo __('Lookup Lists'); ?></h2>
    <table cellpadding="0" cellspacing="0" class="table">
        <thead>
            <tr>
                <th><?php echo $this->Paginator->sort('id'); ?></th>
                <th><?php echo $this->Paginator->sort('name'); ?></th>
                <th><?php echo $this->Paginator->sort('slug'); ?></th>
                <th><?php echo $this->Paginator->sort('public'); ?></th>
                <th class="actions"><?php echo __('Actions'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($lookupLists as $lookupList): ?>
                <tr>
                    <td><?php echo h($lookupList['LookupList']['id']); ?>&nbsp;</td>
                    <td><?php echo h($lookupList['LookupList']['name']); ?>&nbsp;</td>
                    <td><?php echo h($lookupList['LookupList']['slug']); ?>&nbsp;</td>
                    <td><?php echo h($lookupList['LookupList']['public']); ?>&nbsp;</td>
                    <td class="actions">
                        <?php echo $this->Html->link(__('View'), array('action' => 'view', $lookupList['LookupList']['id'])); ?>
                        <?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $lookupList['LookupList']['id'])); ?>
                        <?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $lookupList['LookupList']['id']), array(), __('Are you sure you want to delete # %s?', $lookupList['LookupList']['id'])); ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <p>
        <?php
        echo $this->Paginator->counter(array(
            'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
        ));
        ?>	</p>
    <div class="paging">
        <?php
        echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
        echo $this->Paginator->numbers(array('separator' => ''));
        echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
        ?>
    </div>
</div>
<div class="actions">
    <h3><?php echo __('Actions'); ?></h3>
    <ul>
        <li><?php echo $this->Html->link(__('New Lookup List'), array('controller' => 'lookup_lists', 'action' => 'add')); ?></li>
        <li><?php echo $this->Html->link(__('List Lookup List Items'), array('controller' => 'lookup_list_items', 'action' => 'index')); ?> </li>
        <li><?php echo $this->Html->link(__('New Lookup List Item'), array('controller' => 'lookup_list_items', 'action' => 'add')); ?> </li>
        <li><?php echo $this->Html->link(__('Export'), array('controller' => 'lookup_lists', 'action' => 'export')); ?></li>
        <li><?php echo $this->Html->link(__('Import'), array('controller' => 'lookup_lists', 'action' => 'import')); ?></li>
    </ul>
</div>
