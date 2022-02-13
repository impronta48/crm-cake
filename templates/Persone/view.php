<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Persone $persone
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Persone'), ['action' => 'edit', $persone->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Persone'), ['action' => 'delete', $persone->id], ['confirm' => __('Are you sure you want to delete # {0}?', $persone->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Persone'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Persone'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="persone view content">
            <h3><?= h($persone->id) ?></h3>
            <table>
                <tr>
                    <th><?= __('Nome') ?></th>
                    <td><?= h($persone->Nome) ?></td>
                </tr>
                <tr>
                    <th><?= __('Cognome') ?></th>
                    <td><?= h($persone->Cognome) ?></td>
                </tr>
                <tr>
                    <th><?= __('Citta') ?></th>
                    <td><?= h($persone->Citta) ?></td>
                </tr>
                <tr>
                    <th><?= __('Provincia') ?></th>
                    <td><?= h($persone->Provincia) ?></td>
                </tr>
                <tr>
                    <th><?= __('Nazione') ?></th>
                    <td><?= h($persone->Nazione) ?></td>
                </tr>
                <tr>
                    <th><?= __('CAP') ?></th>
                    <td><?= h($persone->CAP) ?></td>
                </tr>
                <tr>
                    <th><?= __('TelefonoDomicilio') ?></th>
                    <td><?= h($persone->TelefonoDomicilio) ?></td>
                </tr>
                <tr>
                    <th><?= __('TelefonoUfficio') ?></th>
                    <td><?= h($persone->TelefonoUfficio) ?></td>
                </tr>
                <tr>
                    <th><?= __('Titolo') ?></th>
                    <td><?= h($persone->Titolo) ?></td>
                </tr>
                <tr>
                    <th><?= __('Carica') ?></th>
                    <td><?= h($persone->Carica) ?></td>
                </tr>
                <tr>
                    <th><?= __('Societa') ?></th>
                    <td><?= h($persone->Societa) ?></td>
                </tr>
                <tr>
                    <th><?= __('SitoWeb') ?></th>
                    <td><?= h($persone->SitoWeb) ?></td>
                </tr>
                <tr>
                    <th><?= __('ModificatoDa') ?></th>
                    <td><?= h($persone->ModificatoDa) ?></td>
                </tr>
                <tr>
                    <th><?= __('EMail') ?></th>
                    <td><?= h($persone->EMail) ?></td>
                </tr>
                <tr>
                    <th><?= __('Fax') ?></th>
                    <td><?= h($persone->Fax) ?></td>
                </tr>
                <tr>
                    <th><?= __('Cellulare') ?></th>
                    <td><?= h($persone->Cellulare) ?></td>
                </tr>
                <tr>
                    <th><?= __('IM') ?></th>
                    <td><?= h($persone->IM) ?></td>
                </tr>
                <tr>
                    <th><?= __('Categorie') ?></th>
                    <td><?= h($persone->Categorie) ?></td>
                </tr>
                <tr>
                    <th><?= __('DisplayName') ?></th>
                    <td><?= h($persone->DisplayName) ?></td>
                </tr>
                <tr>
                    <th><?= __('Piva') ?></th>
                    <td><?= h($persone->piva) ?></td>
                </tr>
                <tr>
                    <th><?= __('Cf') ?></th>
                    <td><?= h($persone->cf) ?></td>
                </tr>
                <tr>
                    <th><?= __('Iban') ?></th>
                    <td><?= h($persone->iban) ?></td>
                </tr>
                <tr>
                    <th><?= __('NomeBanca') ?></th>
                    <td><?= h($persone->NomeBanca) ?></td>
                </tr>
                <tr>
                    <th><?= __('AltraCitta') ?></th>
                    <td><?= h($persone->altraCitta) ?></td>
                </tr>
                <tr>
                    <th><?= __('AltroCap') ?></th>
                    <td><?= h($persone->altroCap) ?></td>
                </tr>
                <tr>
                    <th><?= __('AltraProv') ?></th>
                    <td><?= h($persone->altraProv) ?></td>
                </tr>
                <tr>
                    <th><?= __('AltraNazione') ?></th>
                    <td><?= h($persone->altraNazione) ?></td>
                </tr>
                <tr>
                    <th><?= __('CodiceIPA') ?></th>
                    <td><?= h($persone->codiceIPA) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($persone->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('DataDiNascita') ?></th>
                    <td><?= h($persone->DataDiNascita) ?></td>
                </tr>
                <tr>
                    <th><?= __('UltimoContatto') ?></th>
                    <td><?= h($persone->UltimoContatto) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($persone->modified) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($persone->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('EntePubblico') ?></th>
                    <td><?= $persone->EntePubblico ? __('Yes') : __('No'); ?></td>
                </tr>
            </table>
            <div class="text">
                <strong><?= __('Indirizzo') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($persone->Indirizzo)); ?>
                </blockquote>
            </div>
            <div class="text">
                <strong><?= __('Nota') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($persone->Nota)); ?>
                </blockquote>
            </div>
            <div class="text">
                <strong><?= __('AltroIndirizzo') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($persone->altroIndirizzo)); ?>
                </blockquote>
            </div>
            <div class="text">
                <strong><?= __('IndirizzoPEC') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($persone->indirizzoPEC)); ?>
                </blockquote>
            </div>
        </div>
    </div>
</div>
