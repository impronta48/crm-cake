<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Persone $persone
 */
?>
<div class="row">
  <aside class="col col-md-3">
    <div class="side-nav">
      <h4 class="heading"><?= __('Actions') ?></h4>
      <?= $this->Html->link(__('List Persone'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
    </div>
  </aside>
  <div class="col  col-md-8">
    <div class="persone form content">
      <?= $this->Form->create($persone) ?>
      <fieldset>
        <legend><?= __('Add Persone') ?></legend>
        <?php
        echo $this->Form->control('Nome', ['class' => 'form-control']);
        echo $this->Form->control('Cognome', ['class' => 'form-control']);
        echo $this->Form->control('Indirizzo', ['class' => 'form-control']);
        echo $this->Form->control('Citta', ['class' => 'form-control']);
        echo $this->Form->control('Provincia', ['class' => 'form-control']);
        echo $this->Form->control('Nazione', ['class' => 'form-control']);
        echo $this->Form->control('CAP', ['class' => 'form-control']);
        echo $this->Form->control('tag_list', ['class' => 'form-control']);
        echo $this->Form->control('TelefonoDomicilio', ['class' => 'form-control']);
        echo $this->Form->control('TelefonoUfficio', ['class' => 'form-control']);
        echo $this->Form->control('DataDiNascita', ['empty' => true, 'class' => 'form-control']);
        echo $this->Form->control('UltimoContatto', ['empty' => true, 'class' => 'form-control']);
        echo $this->Form->control('Nota', ['class' => 'form-control']);
        echo $this->Form->control('Titolo', ['class' => 'form-control']);
        echo $this->Form->control('Carica', ['class' => 'form-control']);
        echo $this->Form->control('Societa', ['class' => 'form-control']);
        echo $this->Form->control('SitoWeb', ['class' => 'form-control']);
        echo $this->Form->control('ModificatoDa', ['class' => 'form-control']);
        echo $this->Form->control('EMail', ['class' => 'form-control']);
        echo $this->Form->control('Fax', ['class' => 'form-control']);
        echo $this->Form->control('Cellulare', ['class' => 'form-control']);
        echo $this->Form->control('IM', ['class' => 'form-control']);
        echo $this->Form->control('Categorie', ['class' => 'form-control']);
        echo $this->Form->control('DisplayName', ['class' => 'form-control']);
        echo $this->Form->control('piva', ['class' => 'form-control']);
        echo $this->Form->control('cf', ['class' => 'form-control']);
        echo $this->Form->control('iban', ['class' => 'form-control']);
        echo $this->Form->control('NomeBanca', ['class' => 'form-control']);
        echo $this->Form->control('altroIndirizzo', ['class' => 'form-control']);
        echo $this->Form->control('altraCitta', ['class' => 'form-control']);
        echo $this->Form->control('altroCap', ['class' => 'form-control']);
        echo $this->Form->control('altraProv', ['class' => 'form-control']);
        echo $this->Form->control('altraNazione', ['class' => 'form-control']);
        echo $this->Form->control('EntePubblico', ['class' => 'form-control']);
        echo $this->Form->control('codiceIPA', ['class' => 'form-control']);
        echo $this->Form->control('indirizzoPEC', ['class' => 'form-control']);
        ?>
      </fieldset>
      <?= $this->Form->button(__('Submit'), ['class' => 'form-control btn btn-primary']) ?>
      <?= $this->Form->end() ?>
    </div>
  </div>
</div>