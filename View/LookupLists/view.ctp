<div class="lookupLists view">
<h2><?php echo __('Lookup List'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($lookupList['LookupList']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Slug'); ?></dt>
		<dd>
			<?php echo h($lookupList['LookupList']['slug']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Name'); ?></dt>
		<dd>
			<?php echo h($lookupList['LookupList']['name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($lookupList['LookupList']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($lookupList['LookupList']['modified']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Lookup List'), array('action' => 'edit', $lookupList['LookupList']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Lookup List'), array('action' => 'delete', $lookupList['LookupList']['id']), array(), __('Are you sure you want to delete # %s?', $lookupList['LookupList']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Lookup Lists'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Lookup List'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Lookup List Items'), array('controller' => 'lookup_list_items', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Lookup List Item'), array('controller' => 'lookup_list_items', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php echo __('Related Lookup List Items'); ?></h3>
	<?php if (!empty($lookupList['LookupListItem'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Lookup List Id'); ?></th>
		<th><?php echo __('Item Id'); ?></th>
		<th><?php echo __('Slug'); ?></th>
		<th><?php echo __('Value'); ?></th>
		<th><?php echo __('Display Order'); ?></th>
		<th><?php echo __('Default'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($lookupList['LookupListItem'] as $lookupListItem): ?>
		<tr>
			<td><?php echo $lookupListItem['id']; ?></td>
			<td><?php echo $lookupListItem['lookup_list_id']; ?></td>
			<td><?php echo $lookupListItem['item_id']; ?></td>
			<td><?php echo $lookupListItem['slug']; ?></td>
			<td><?php echo $lookupListItem['value']; ?></td>
			<td><?php echo $lookupListItem['display_order']; ?></td>
			<td><?php echo $lookupListItem['default']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'lookup_list_items', 'action' => 'view', $lookupListItem['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'lookup_list_items', 'action' => 'edit', $lookupListItem['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'lookup_list_items', 'action' => 'delete', $lookupListItem['id']), array(), __('Are you sure you want to delete # %s?', $lookupListItem['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Lookup List Item'), array('controller' => 'lookup_list_items', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
