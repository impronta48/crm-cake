<?php

use Cake\Core\Configure;


$default_sender_name  = Configure::read('MailAdmin');
$default_sender_email  = array_key_first(Configure::read('MailAdmin'));
$default_message  = $this->Text->autoParagraph(Configure::read('DefaultMessage'));
?>
<h1>Invio mail ad un gruppo</h1>

<b>Numero di email che saranno inviate:</b> <?= $count ?>

<?= $this->Form->create(null, ['class' => 'form']) ?>
<?= $this->Form->hidden('ids'); ?>
<?= $this->Form->control('sender_name', ['class' => 'form-control', 'value' => $default_sender_name]) ?>
<?= $this->Form->control('sender_email', ['class' => 'form-control', 'value' => $default_sender_email, 'required' => true]) ?>
<?= $this->Form->control('test', ['class' => 'form-control', 'value' => $default_sender_email]) ?>
<?= $this->Form->control('layout', ['class' => 'form-control', 'value' => 'default', 'required' => true]) ?>
<?= $this->Form->control('subject', ['class' => 'form-control', 'required' => true]) ?>
<?= $this->Form->control('body', ['class' => 'form-control', 'type' => 'textarea', 'rows' => '10', 'value' => $default_message, 'class' => 'editor']) ?>
<b-row class="mt-2">
  <b-col class="float-right">
    <?= $this->Form->button('invia test', ['class' => 'btn btn-outline-info', 'name' => 'invia-test']) ?>
    <?= $this->Form->button('invia', ['class' => 'btn btn-danger', 'name' => 'invia']) ?>
  </b-col>
</b-row>
<?= $this->Form->end() ?>

<?= $this->Html->script('ckeditor/ckeditor', ['block' => true]) ?>
<?= $this->Html->script('add-ckeditor.js', ['block' => true]) ?>