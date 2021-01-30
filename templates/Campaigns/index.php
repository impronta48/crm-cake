<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Campaign[]|\Cake\Collection\CollectionInterface $campaigns
 */
?>
<div class="campaigns index content">
  <h3>Campagne Mail</h3>
  <div class="table table-responsive table-striped">
    <table>
      <thead>
        <tr>
          <th><?= $this->Paginator->sort('id') ?></th>
          <th><?= $this->Paginator->sort('subject') ?></th>
          <th><?= $this->Paginator->sort('querystring') ?></th>
          <th><?= $this->Paginator->sort('sent') ?></th>
          <th><?= $this->Paginator->sort('modified') ?></th>
          <th class="actions"><?= __('Actions') ?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($campaigns as $campaign) : ?>
          <tr>
            <td><?= $this->Number->format($campaign->id) ?></td>
            <td><?= h($campaign->subject) ?></td>
            <td><?= h($campaign->querystring) ?></td>
            <td>
              <?php if ($campaign->sent) : ?>
                <b-badge variant="success">spedita</b-badge>
              <?php else : ?>
                <b-badge variant="danger">non spedita</b-badge>
              <?php endif ?>
            </td>
            <td><?= h($campaign->modified) ?></td>
            <td class="actions">
              <?= $this->Html->link(__('Edit'), ['action' => 'edit', $campaign->id]) ?>
              <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $campaign->id], ['confirm' => __('Are you sure you want to delete # {0}?', $campaign->id)]) ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <div class="paginator">
    <ul class="pagination">
      <?= $this->Paginator->first('<< ' . __('first')) ?>
      <?= $this->Paginator->prev('< ' . __('previous')) ?>
      <?= $this->Paginator->numbers() ?>
      <?= $this->Paginator->next(__('next') . ' >') ?>
      <?= $this->Paginator->last(__('last') . ' >>') ?>
    </ul>
    <p><?= $this->Paginator->counter(__('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')) ?></p>
  </div>
</div>