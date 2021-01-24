<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Persone[]|\Cake\Collection\CollectionInterface $persone
 */
?>
<div class="persone index content">
    <?= $this->Html->link(__('New Persone'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Persone') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('Nome') ?></th>
                    <th><?= $this->Paginator->sort('Cognome') ?></th>
                    <th><?= $this->Paginator->sort('Citta') ?></th>
                    <th><?= $this->Paginator->sort('Provincia') ?></th>
                    <th><?= $this->Paginator->sort('Nazione') ?></th>
                    <th><?= $this->Paginator->sort('CAP') ?></th>
                    <th><?= $this->Paginator->sort('TelefonoDomicilio') ?></th>
                    <th><?= $this->Paginator->sort('TelefonoUfficio') ?></th>
                    <th><?= $this->Paginator->sort('DataDiNascita') ?></th>
                    <th><?= $this->Paginator->sort('UltimoContatto') ?></th>
                    <th><?= $this->Paginator->sort('Titolo') ?></th>
                    <th><?= $this->Paginator->sort('Carica') ?></th>
                    <th><?= $this->Paginator->sort('Societa') ?></th>
                    <th><?= $this->Paginator->sort('SitoWeb') ?></th>
                    <th><?= $this->Paginator->sort('ModificatoDa') ?></th>
                    <th><?= $this->Paginator->sort('EMail') ?></th>
                    <th><?= $this->Paginator->sort('Fax') ?></th>
                    <th><?= $this->Paginator->sort('Cellulare') ?></th>
                    <th><?= $this->Paginator->sort('IM') ?></th>
                    <th><?= $this->Paginator->sort('Categorie') ?></th>
                    <th><?= $this->Paginator->sort('DisplayName') ?></th>
                    <th><?= $this->Paginator->sort('piva') ?></th>
                    <th><?= $this->Paginator->sort('cf') ?></th>
                    <th><?= $this->Paginator->sort('modified') ?></th>
                    <th><?= $this->Paginator->sort('created') ?></th>
                    <th><?= $this->Paginator->sort('iban') ?></th>
                    <th><?= $this->Paginator->sort('NomeBanca') ?></th>
                    <th><?= $this->Paginator->sort('altraCitta') ?></th>
                    <th><?= $this->Paginator->sort('altroCap') ?></th>
                    <th><?= $this->Paginator->sort('altraProv') ?></th>
                    <th><?= $this->Paginator->sort('altraNazione') ?></th>
                    <th><?= $this->Paginator->sort('EntePubblico') ?></th>
                    <th><?= $this->Paginator->sort('codiceIPA') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($persone as $persone): ?>
                <tr>
                    <td><?= $this->Number->format($persone->id) ?></td>
                    <td><?= h($persone->Nome) ?></td>
                    <td><?= h($persone->Cognome) ?></td>
                    <td><?= h($persone->Citta) ?></td>
                    <td><?= h($persone->Provincia) ?></td>
                    <td><?= h($persone->Nazione) ?></td>
                    <td><?= h($persone->CAP) ?></td>
                    <td><?= h($persone->TelefonoDomicilio) ?></td>
                    <td><?= h($persone->TelefonoUfficio) ?></td>
                    <td><?= h($persone->DataDiNascita) ?></td>
                    <td><?= h($persone->UltimoContatto) ?></td>
                    <td><?= h($persone->Titolo) ?></td>
                    <td><?= h($persone->Carica) ?></td>
                    <td><?= h($persone->Societa) ?></td>
                    <td><?= h($persone->SitoWeb) ?></td>
                    <td><?= h($persone->ModificatoDa) ?></td>
                    <td><?= h($persone->EMail) ?></td>
                    <td><?= h($persone->Fax) ?></td>
                    <td><?= h($persone->Cellulare) ?></td>
                    <td><?= h($persone->IM) ?></td>
                    <td><?= h($persone->Categorie) ?></td>
                    <td><?= h($persone->DisplayName) ?></td>
                    <td><?= h($persone->piva) ?></td>
                    <td><?= h($persone->cf) ?></td>
                    <td><?= h($persone->modified) ?></td>
                    <td><?= h($persone->created) ?></td>
                    <td><?= h($persone->iban) ?></td>
                    <td><?= h($persone->NomeBanca) ?></td>
                    <td><?= h($persone->altraCitta) ?></td>
                    <td><?= h($persone->altroCap) ?></td>
                    <td><?= h($persone->altraProv) ?></td>
                    <td><?= h($persone->altraNazione) ?></td>
                    <td><?= h($persone->EntePubblico) ?></td>
                    <td><?= h($persone->codiceIPA) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $persone->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $persone->id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $persone->id], ['confirm' => __('Are you sure you want to delete # {0}?', $persone->id)]) ?>
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
