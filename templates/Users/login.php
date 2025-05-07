<?php
/**
 * @var \App\View\AppView $this
 */
?>
<div class="users form content">
    <?= $this->Form->create() ?>
    <fieldset>
        <legend><?= __('Please enter your username and password') ?></legend>
        <?= $this->Form->control('username', ['required' => true]) ?>
        <?= $this->Form->control('password', ['required' => true]) ?>
    </fieldset>
    <?= $this->Form->button(__('Login')); ?>
    <?= $this->Form->end() ?>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('.users.form form');
        form.addEventListener('submit', function(e) {
            const passwordField = document.getElementById('password');
            if (!passwordField.value.trim()) {
                e.preventDefault();
                alert('Password cannot be empty');
            }
        });
    });
</script>
