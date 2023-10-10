<?= $this->Form->create() ?>
<?= $this->Form->file('excelfile'); ?>
<?= $this->Form->input('tags'); ?>
<?= $this->Form->submit("importa") ?>