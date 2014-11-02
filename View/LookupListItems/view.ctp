<div class="lookupListItems view">
<h2><?php echo __('Lookup List Item'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($lookupListItem['LookupListItem']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Lookup List'); ?></dt>
		<dd>
			<?php echo $this->Html->link($lookupListItem['LookupList']['name'], array('controller' => 'lookup_lists', 'action' => 'view', $lookupListItem['LookupList']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Item Id'); ?></dt>
		<dd>
			<?php echo h($lookupListItem['LookupListItem']['item_id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Slug'); ?></dt>
		<dd>
			<?php echo h($lookupListItem['LookupListItem']['slug']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Value'); ?></dt>
		<dd>
			<?php echo h($lookupListItem['LookupListItem']['value']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Display Order'); ?></dt>
		<dd>
			<?php echo h($lookupListItem['LookupListItem']['display_order']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Default'); ?></dt>
		<dd>
			<?php echo h($lookupListItem['LookupListItem']['default']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Public'); ?></dt>
		<dd>
			<?php echo h($lookupListItem['LookupListItem']['public']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Lookup List Item'), array('action' => 'edit', $lookupListItem['LookupListItem']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Lookup List Item'), array('action' => 'delete', $lookupListItem['LookupListItem']['id']), array(), __('Are you sure you want to delete # %s?', $lookupListItem['LookupListItem']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Lookup List Items'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Lookup List Item'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Lookup Lists'), array('controller' => 'lookup_lists', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Lookup List'), array('controller' => 'lookup_lists', 'action' => 'add')); ?> </li>
	</ul>
</div>
