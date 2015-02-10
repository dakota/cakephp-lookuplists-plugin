<ol class="breadcrumb">
    <li><a href="/">Home</a></li>
    <li class="active">Lists</li>
</ol>

<div class="lookupLists index">
    <h2><?php echo __('Lookup Lists'); ?></h2>
    <?php echo $this->Html->link('Create New List', ['controller' => 'LookupLists', 'action' => 'add'], ['class' => 'btn btn-primary btn-xs']) ?>
    <?php //echo $this->Html->link('Export', array('controller' => 'LookupLists', 'action' => 'export'), array('class' => 'btn btn-info btn-xs')) ?>
    <?php //echo $this->Html->link('Import', array('controller' => 'LookupLists', 'action' => 'import'), array('class' => 'btn btn-info btn-xs')) ?>

    <table cellpadding="0" cellspacing="0" class="table">
        <thead>
            <tr>
                <th><?php echo $this->Paginator->sort('id'); ?></th>
                <th><?php echo $this->Paginator->sort('name'); ?></th>
                <th><?php echo $this->Paginator->sort('slug'); ?></th>
                <th><?php echo $this->Paginator->sort('public'); ?></th>
                <th class="actions" style="width:100px;"><?php echo __('Actions'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($lookupLists as $lookupList): ?>
                <tr>
                    <td><?php echo h($lookupList->id); ?>&nbsp;</td>
                    <td><?php echo $this->html->link($lookupList->name, ['plugin' => 'LookupLists', 'controller' => 'LookupLists', 'action' => 'edit', $lookupList->id]); ?>&nbsp;</td>
                    <td><?php echo h($lookupList->slug); ?>&nbsp;</td>
                    <td><?php echo $lookupList->public ? 'Yes' : 'No'; ?>&nbsp;</td>
                    <td class="actions">
                        <?php echo $this->Html->link(__('Edit'), ['plugin' => 'LookupLists', 'action' => 'edit', $lookupList->id], ['class' => 'btn btn-primary btn-xs']); ?>
                        <?php echo $this->Form->postLink(__('Delete'), ['plugin' => 'LookupLists', 'action' => 'delete', $lookupList->id], ['class' => 'btn btn-danger btn-xs'], __('Are you sure you want to delete # {0}?', $lookupList->id)); ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!--start counts and paging-->
    <nav>
        <ul class="pagination">
            <?php
            echo $this->Paginator->first('&laquo;', ['escape' => false, 'tag' => 'li', 'disabledTag' => 'a'], null, ['escape' => false, 'class' => 'disabled', 'tag' => 'li', 'disabledTag' => 'a']);
            echo $this->Paginator->prev('&laquo;', ['escape' => false, 'tag' => 'li', 'disabledTag' => 'a'], null, ['escape' => false, 'class' => 'disabled', 'tag' => 'li', 'disabledTag' => 'a']);
            echo $this->Paginator->numbers(['separator' => '', 'class' => 'paging', 'tag' => 'li', 'currentClass' => 'active', 'currentTag' => 'a']);
            echo $this->Paginator->next('&raquo;', ['escape' => false, 'tag' => 'li', 'disabledTag' => 'a'], null, ['escape' => false, 'class' => 'disabled', 'tag' => 'li', 'disabledTag' => 'a']);
            echo $this->Paginator->last('&raquo;', ['escape' => false, 'tag' => 'li', 'disabledTag' => 'a'], null, ['escape' => false, 'class' => 'disabled', 'tag' => 'li', 'disabledTag' => 'a']);
            ?>
        </ul>
    </nav>
    <!--end counts and paging-->
</div>

