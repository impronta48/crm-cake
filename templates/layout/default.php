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
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,300italic,700,700italic">

  <!-- CSS Reset -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.css">

  <!-- Milligram CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/milligram/1.4.1/milligram.css">

  <!-- Load Vue followed by BootstrapVue -->
  <?php (Configure::read('debug') == false) ? $mn = '.min' : $mn = '';
  $mn = ''; ?>

  <?= $this->fetch('meta') ?>
  <?= $this->fetch('css') ?>
</head>

<body>
        <main role="main" class="container">
          <div class="mt-2"><?= $this->Flash->render() ?></div>
          <?= $this->fetch('content') ?>

        </main><!-- /.container -->

    <footer class="footer bg-dark text-white my-auto">
      <div class="container p-2">
        Cake CRM - &copy; <?=  date('Y'); ?> 
        | <a href="https://mobilitysquare.eu" target="_blank">MobilitySquare srl - Società Benefit</a>
        |
        <a href="https://github.com/impronta48/crm-cake" target="_blank">
          GitHub <img src="https://github.githubassets.com/images/modules/logos_page/GitHub-Mark.png" alt="GitHub" style="width: 20px; height: 20px;">
        </a>
      </div>
    </footer>
  </div>

  <?= $this->fetch('script') ?>
</body>

</html>