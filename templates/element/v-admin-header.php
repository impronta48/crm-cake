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
      <b-navbar-brand href="<?= $this->Url->build('/') ?>">
        <?= $this->Html->image(Configure::read('logo'), [
          'height' => '80px'
        ]) ?>
      </b-navbar-brand>

      <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>

      <b-collapse id="nav-collapse" is-nav>
        <b-navbar-nav>
          <b-nav-item href="/persone">
            <b-icon-person-badge></b-icon-person-badge> Persone
          </b-nav-item>
          <b-nav-item href="/campaigns">
            <b-icon-envelope></b-icon-envelope> Campagne
          </b-nav-item>
          <b-nav-item href="/opportunities" disabled>
            <b-icon-bullseye></b-icon-bullseye>
            Opportunità
          </b-nav-item>
          <b-nav-item href="<?= Configure::read('Trello.sales') ?>" target="trello">
            <b-icon-kanban></b-icon-kanban>
            Trello
          </b-nav-item>
        </b-navbar-nav>

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