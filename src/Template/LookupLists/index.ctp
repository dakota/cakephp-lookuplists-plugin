<ol class="breadcrumb">
    <li><a href="/">Home</a></li>
    <li class="active">Lists</li>
</ol>

<div class="lookupLists index">


    <h2><?php echo __('Lookup Lists'); ?></h2>
    <?php echo $this->Html->link('Create New List', array('controller' => 'lookup_lists', 'action' => 'add'), array('class' => 'btn btn-primary btn-xs')) ?>
    <?php //echo $this->Html->link('Export', array('controller' => 'lookup_lists', 'action' => 'export'), array('class' => 'btn btn-info btn-xs')) ?>
    <?php //echo $this->Html->link('Import', array('controller' => 'lookup_lists', 'action' => 'import'), array('class' => 'btn btn-info btn-xs')) ?>



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
                    <td><?php echo h($lookupList['LookupList']['id']); ?>&nbsp;</td>
                    <td><?php echo $this->html->link($lookupList['LookupList']['name'], array('plugin' => 'lookup_lists', 'controller' => 'lookup_lists', 'action' => 'edit', $lookupList['LookupList']['id'])); ?>&nbsp;</td>
                    <td><?php echo h($lookupList['LookupList']['slug']); ?>&nbsp;</td>
                    <td><?php echo $lookupList['LookupList']['public'] ? 'Yes' : 'No'; ?>&nbsp;</td>
                    <td class="actions">
                        <?php echo $this->Html->link(__('Edit'), array('plugin' => 'lookup_lists', 'action' => 'edit', $lookupList['LookupList']['id']), array('class' => 'btn btn-primary btn-xs')); ?>
                        <?php echo $this->Form->postLink(__('Delete'), array('plugin' => 'lookup_lists', 'action' => 'delete', $lookupList['LookupList']['id']), array('class' => 'btn btn-danger btn-xs'), __('Are you sure you want to delete # {0}?', $lookupList['LookupList']['id'])); ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!--start counts and paging-->
    <nav>
        <ul class="pagination">
            <?php
            echo $this->Paginator->first('&laquo;', array('escape' => false, 'tag' => 'li', 'disabledTag' => 'a'), null, array('escape' => false, 'class' => 'disabled', 'tag' => 'li', 'disabledTag' => 'a'));
            echo $this->Paginator->prev('&laquo;', array('escape' => false, 'tag' => 'li', 'disabledTag' => 'a'), null, array('escape' => false, 'class' => 'disabled', 'tag' => 'li', 'disabledTag' => 'a'));
            echo $this->Paginator->numbers(array('separator' => '', 'class' => 'paging', 'tag' => 'li', 'currentClass' => 'active', 'currentTag' => 'a'));
            echo $this->Paginator->next('&raquo;', array('escape' => false, 'tag' => 'li', 'disabledTag' => 'a'), null, array('escape' => false, 'class' => 'disabled', 'tag' => 'li', 'disabledTag' => 'a'));
            echo $this->Paginator->last('&raquo;', array('escape' => false, 'tag' => 'li', 'disabledTag' => 'a'), null, array('escape' => false, 'class' => 'disabled', 'tag' => 'li', 'disabledTag' => 'a'));
            ?>
        </ul>
    </nav>
    <!--end counts and paging-->
</div>

