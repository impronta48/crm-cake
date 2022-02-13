<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Attivita $attivita
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('List Attivita'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="attivita form content">
            <?= $this->Form->create($attivita) ?>
            <fieldset>
                <legend><?= __('Add Attivita') ?></legend>
                <?php
                    echo $this->Form->control('name');
                    echo $this->Form->control('progetto_id', ['options' => $progetti, 'empty' => true]);
                    echo $this->Form->control('cliente_id');
                    echo $this->Form->control('DataPresentazione', ['empty' => true]);
                    echo $this->Form->control('DataApprovazione', ['empty' => true]);
                    echo $this->Form->control('DataInizio', ['empty' => true]);
                    echo $this->Form->control('DataFine', ['empty' => true]);
                    echo $this->Form->control('DataFinePrevista', ['empty' => true]);
                    echo $this->Form->control('NumOre');
                    echo $this->Form->control('NumOreConsuntivo');
                    echo $this->Form->control('OffertaAlCliente');
                    echo $this->Form->control('ImportoAcquisito');
                    echo $this->Form->control('NettoOra');
                    echo $this->Form->control('OreUfficio');
                    echo $this->Form->control('MotivazioneRit');
                    echo $this->Form->control('Utile');
                    echo $this->Form->control('Note');
                    echo $this->Form->control('area_id', ['options' => $aree, 'empty' => true]);
                    echo $this->Form->control('azienda_id');
                    echo $this->Form->control('chiusa');
                    echo $this->Form->control('alias');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
