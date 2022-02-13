<?php

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 * @var \App\View\AppView $this
 */
//Invece di milligram uso questo fork che ha qualche elemento in più
//https://kidgodzilla.github.io/minimalist-css-framework/

use Cake\Core\Configure;

?>
<!DOCTYPE html>
<html>

<head>
  <?= $this->Html->charset() ?>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>
    CRM
    <?= $this->fetch('title') ?>
  </title>
  <?= $this->Html->meta('icon') ?>

  <link href="https://fonts.googleapis.com/css?family=Raleway:400,700" rel="stylesheet">

  <!-- Load required Bootstrap and BootstrapVue CSS -->
  <link type="text/css" rel="stylesheet" href="/js/node_modules/bootstrap/dist/css/bootstrap.min.css" />
  <link type="text/css" rel="stylesheet" href="/js/node_modules/bootstrap-vue/dist/bootstrap-vue.min.css" />
  <?= $this->Html->css('style') ?>

  <!-- Load polyfills to support older browsers -->
  <script src="//polyfill.io/v3/polyfill.min.js?features=es2015%2CIntersectionObserver" crossorigin="anonymous"></script>

  <!-- Load Vue followed by BootstrapVue -->
  <?php (Configure::read('debug') == false) ? $mn = '.min' : $mn = '';
  $mn = ''; ?>

  <script src="/js/node_modules/vue/dist/vue<?= $mn ?>.js"></script>
  <script src="/js/node_modules/bootstrap-vue/dist/bootstrap-vue.min.js"></script>

  <!-- Load the following for BootstrapVueIcons support -->
  <script src="/js/node_modules/bootstrap-vue/dist/bootstrap-vue-icons.min.js"></script>

  <?= $this->fetch('meta') ?>
  <?= $this->fetch('css') ?>
</head>

<body>
  <div id="app" class="d-flex flex-column min-vh-100">

    <div v-cloak class="flex-grow-1">

      <div class="v-cloak--inline">
        <!-- Parts that will be visible before compiled your HTML -->
        <div class="spinner"></div>
      </div>

      <div class="v-cloak--hidden">
        <!-- Parts that will be visible After compiled your HTML -->
        <?= $this->element('v-admin-header'); ?>

        <main role="main" class="container">
          <div class="mt-2"><?= $this->Flash->render() ?></div>
          <?= $this->fetch('content') ?>

        </main><!-- /.container -->
      </div>
    </div>

    <footer class="footer bg-dark text-white my-auto">
      <div class="container p-2">
        BikeSquare CRM <small>&copy; 2021 BikeSquare srl</small>
      </div>
    </footer>
  </div>

  <?= $this->fetch('pre-vue') ?>
  <?= $this->element('v-autoload')  //Carica automaticamente lo script /theme/js/vue/{Controller}/{action}.js
  ?>
  <?= $this->fetch('script') ?>
</body>

</html>