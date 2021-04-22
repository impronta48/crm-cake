<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Attivita[]|\Cake\Collection\CollectionInterface $attivita
 */
?>
<div class="attivita index content">
    <?= $this->Html->link(__('New Attivita'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Attivita') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('name') ?></th>
                    <th><?= $this->Paginator->sort('progetto_id') ?></th>
                    <th><?= $this->Paginator->sort('cliente_id') ?></th>
                    <th><?= $this->Paginator->sort('DataPresentazione') ?></th>
                    <th><?= $this->Paginator->sort('DataApprovazione') ?></th>
                    <th><?= $this->Paginator->sort('DataInizio') ?></th>
                    <th><?= $this->Paginator->sort('DataFine') ?></th>
                    <th><?= $this->Paginator->sort('DataFinePrevista') ?></th>
                    <th><?= $this->Paginator->sort('NumOre') ?></th>
                    <th><?= $this->Paginator->sort('NumOreConsuntivo') ?></th>
                    <th><?= $this->Paginator->sort('OffertaAlCliente') ?></th>
                    <th><?= $this->Paginator->sort('ImportoAcquisito') ?></th>
                    <th><?= $this->Paginator->sort('NettoOra') ?></th>
                    <th><?= $this->Paginator->sort('OreUfficio') ?></th>
                    <th><?= $this->Paginator->sort('Utile') ?></th>
                    <th><?= $this->Paginator->sort('area_id') ?></th>
                    <th><?= $this->Paginator->sort('azienda_id') ?></th>
                    <th><?= $this->Paginator->sort('chiusa') ?></th>
                    <th><?= $this->Paginator->sort('created') ?></th>
                    <th><?= $this->Paginator->sort('modified') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($attivita as $attivita): ?>
                <tr>
                    <td><?= $this->Number->format($attivita->id) ?></td>
                    <td><?= h($attivita->name) ?></td>
                    <td><?= $attivita->has('progetto') ? $this->Html->link($attivita->progetto->name, ['controller' => 'Progetti', 'action' => 'view', $attivita->progetto->id]) : '' ?></td>
                    <td><?= $this->Number->format($attivita->cliente_id) ?></td>
                    <td><?= h($attivita->DataPresentazione) ?></td>
                    <td><?= h($attivita->DataApprovazione) ?></td>
                    <td><?= h($attivita->DataInizio) ?></td>
                    <td><?= h($attivita->DataFine) ?></td>
                    <td><?= h($attivita->DataFinePrevista) ?></td>
                    <td><?= $this->Number->format($attivita->NumOre) ?></td>
                    <td><?= $this->Number->format($attivita->NumOreConsuntivo) ?></td>
                    <td><?= $this->Number->format($attivita->OffertaAlCliente) ?></td>
                    <td><?= $this->Number->format($attivita->ImportoAcquisito) ?></td>
                    <td><?= $this->Number->format($attivita->NettoOra) ?></td>
                    <td><?= $this->Number->format($attivita->OreUfficio) ?></td>
                    <td><?= $this->Number->format($attivita->Utile) ?></td>
                    <td><?= $attivita->has('area') ? $this->Html->link($attivita->area->name, ['controller' => 'Aree', 'action' => 'view', $attivita->area->id]) : '' ?></td>
                    <td><?= $this->Number->format($attivita->azienda_id) ?></td>
                    <td><?= h($attivita->chiusa) ?></td>
                    <td><?= h($attivita->created) ?></td>
                    <td><?= h($attivita->modified) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $attivita->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $attivita->id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $attivita->id], ['confirm' => __('Are you sure you want to delete # {0}?', $attivita->id)]) ?>
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
