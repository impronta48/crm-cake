<?php

use Cake\Core\Configure;
use Cake\Routing\Router;
?>
<?php
//Carico le voci di menu extra portate da ogni plugin nel proprio element
$this->element('v-admin-extra-main-menu'); ?>
<div>
  <b-navbar toggleable="lg" variant="light">
    <div class="container">
      <b-navbar-brand href="<?= $this->Url->build('/') ?>" target="sito_pubblico">
        <?= $this->Html->image(Configure::read('logo'), [
          'height' => '80px'
        ]) ?>
      </b-navbar-brand>

      <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>

      <b-collapse id="nav-collapse" is-nav>
        <!-- Right aligned nav items -->
        <b-navbar-nav class="ml-auto">
          <b-nav-item href="<?= Configure::read('urlPrenota') ?>" target="preview">
            Prenota B²RM
            <b-icon-globe></b-icon-globe>
          </b-nav-item>
        </b-navbar-nav>
      </b-collapse>
    </div>
  </b-navbar>
</div>