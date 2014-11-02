<div class="lookupListItems index">
	<h2><?php echo __('Lookup List Items'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<thead>
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('lookup_list_id'); ?></th>
			<th><?php echo $this->Paginator->sort('item_id'); ?></th>
			<th><?php echo $this->Paginator->sort('slug'); ?></th>
			<th><?php echo $this->Paginator->sort('value'); ?></th>
			<th><?php echo $this->Paginator->sort('display_order'); ?></th>
			<th><?php echo $this->Paginator->sort('default'); ?></th>
			<th><?php echo $this->Paginator->sort('public'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($lookupListItems as $lookupListItem): ?>
	<tr>
		<td><?php echo h($lookupListItem['LookupListItem']['id']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($lookupListItem['LookupList']['name'], array('controller' => 'lookup_lists', 'action' => 'view', $lookupListItem['LookupList']['id'])); ?>
		</td>
		<td><?php echo h($lookupListItem['LookupListItem']['item_id']); ?>&nbsp;</td>
		<td><?php echo h($lookupListItem['LookupListItem']['slug']); ?>&nbsp;</td>
		<td><?php echo h($lookupListItem['LookupListItem']['value']); ?>&nbsp;</td>
		<td><?php echo h($lookupListItem['LookupListItem']['display_order']); ?>&nbsp;</td>
		<td><?php echo h($lookupListItem['LookupListItem']['default']); ?>&nbsp;</td>
		<td><?php echo h($lookupListItem['LookupListItem']['public']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $lookupListItem['LookupListItem']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $lookupListItem['LookupListItem']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $lookupListItem['LookupListItem']['id']), array(), __('Are you sure you want to delete # %s?', $lookupListItem['LookupListItem']['id'])); ?>
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
		<li><?php echo $this->Html->link(__('New Lookup List Item'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Lookup Lists'), array('controller' => 'lookup_lists', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Lookup List'), array('controller' => 'lookup_lists', 'action' => 'add')); ?> </li>
	</ul>
</div>
