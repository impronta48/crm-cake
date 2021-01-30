<?php

use Cake\Core\Configure;


$default_sender_name  = Configure::read('MailAdmin');
$default_sender_email  = array_key_first(Configure::read('MailAdmin'));
$default_message  = $this->Text->autoParagraph(Configure::read('DefaultMessage'));
?>
<b-breadcrumb>
  <b-breadcrumb-item href="/persone?<?= $campaign->querystring ?>">Persone</b-breadcrumb-item>
  <b-breadcrumb-item active>Invio Mail</b-breadcrumb-item>
</b-breadcrumb>

<h1>Invio mail ad un gruppo</h1>

<b-alert variant="warning" show>
  <b-icon icon="exclamation-circle-fill" variant="warning"></b-icon>
  <b>Numero di email che saranno inviate:</b> <?= $count ?>
</b-alert>

<?= $this->Form->create($campaign, ['class' => 'form']) ?>
<?= $this->Form->hidden('ids'); ?>
<b-row>
  <b-col>
    <?= $this->Form->control('sender_name', ['class' => 'form-control', 'value' => $default_sender_name]) ?>
  </b-col>
  <b-col>
    <?= $this->Form->control('sender_email', ['class' => 'form-control', 'value' => $default_sender_email, 'required' => true]) ?>
  </b-col>
</b-row>
<?= $this->Form->control('layout', ['class' => 'form-control', 'value' => 'default', 'required' => true]) ?>
<?= $this->Form->control('subject', ['class' => 'form-control', 'required' => true]) ?>
<?= $this->Form->control('body', ['class' => 'form-control', 'type' => 'textarea', 'rows' => '10', 'value' => $default_message, 'class' => 'editor']) ?>
<hr>
<h2>Invio Test</h2>
<b-row align-v="end">
  <b-col>
    <?= $this->Form->control('test_email', ['class' => 'form-control', 'default' => $default_sender_email, 'label' => 'Indirizzo email a cui inviare i test']) ?>
  </b-col>
  <b-col>
    <?= $this->Form->button('Invia test', ['class' => 'btn btn-outline-info', 'name' => 'invia-test']) ?>
  </b-col>
</b-row>

<hr>
<b-row class="mt-2">
  <b-col class="float-right">
    <?= $this->Form->button('Salva bozza', ['class' => 'btn btn-success', 'name' => 'salva']) ?>

    <?= $this->Form->button("Invia $count email ", ['class' => 'btn btn-danger', 'name' => 'invia']) ?>
  </b-col>
</b-row>
<?= $this->Form->end() ?>

<?= $this->Html->script('ckeditor/ckeditor', ['block' => true]) ?>
<?= $this->Html->script('add-ckeditor.js', ['block' => true]) ?>