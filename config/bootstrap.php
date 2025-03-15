<?php

declare(strict_types=1);

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
 * @since         0.10.8
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

/*
 * Configure paths required to find CakePHP + general filepath constants
 */
require __DIR__ . '/paths.php';

/*
 * Bootstrap CakePHP.
 *
 * Does the various bits of setup that CakePHP needs to do.
 * This includes:
 *
 * - Registering the CakePHP autoloader.
 * - Setting the default application paths.
 */
require CORE_PATH . 'config' . DS . 'bootstrap.php';

use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\Core\Configure\Engine\PhpConfig;
use Cake\Datasource\ConnectionManager;
use Cake\Error\ConsoleErrorHandler;
use Cake\Error\ErrorTrap;
use Cake\Error\ExceptionTrap;
use Cake\Http\ServerRequest;
use Cake\Log\Log;
use Cake\Mailer\Mailer;
use Cake\Mailer\TransportFactory;
use Cake\Routing\Router;
use Cake\Utility\Inflector;
use Cake\Utility\Security;

require CAKE . 'functions.php';
/*
 * See https://github.com/josegonzalez/php-dotenv for API details.
 *
 * Uncomment block of code below if you want to use `.env` file during development.
 * You should copy `config/.env.example` to `config/.env` and set/modify the
 * variables as required.
 *
 * The purpose of the .env file is to emulate the presence of the environment
 * variables like they would be present in production.
 *
 * If you use .env files, be careful to not commit them to source control to avoid
 * security risks. See https://github.com/josegonzalez/php-dotenv#general-security-information
 * for more information for recommended practices.
*/
// if (!env('APP_NAME') && file_exists(CONFIG . '.env')) {
//     $dotenv = new \josegonzalez\Dotenv\Loader([CONFIG . '.env']);
//     $dotenv->parse()
//         ->putenv()
//         ->toEnv()
//         ->toServer();
// }

/*
 * Read configuration file and inject configuration into various
 * CakePHP classes.
 *
 * By default there is only one configuration file. It is often a good
 * idea to create multiple configuration files, and separate the configuration
 * that changes from configuration that does not. This makes deployment simpler.
 */

 $path = conf_path();
 Configure::write('confPath', $path);
 try {
   Configure::config('default', new PhpConfig());
   Configure::load('app', 'default', false);
   Configure::config('special', new PhpConfig(CONFIG . $path . DS));
    // cerco tutti i file di condifgurazione nella cartella del sito
    foreach (new DirectoryIterator(CONFIG  . conf_path() . '/') as $fileInfo) {
      if ($fileInfo->getExtension() == 'php') {
        Configure::load( $fileInfo->getBasename(".php"), 'special' );
      }
    }
 } catch (\Exception $e) {
   exit($e->getMessage() . "\n");
 }

 

/*
 * Load an environment local configuration file to provide overrides to your configuration.
 * Notice: For security reasons app_local.php **should not** be included in your git repo.
 */
if (file_exists(CONFIG . 'app_local.php')) {
  Configure::load('app_local', 'default');
}

/*
 * When debug = true the metadata cache should only last
 * for a short time.
 */
if (Configure::read('debug')) {
  Configure::write('Cache._cake_model_.duration', '+2 minutes');  
  // disable router cache during development
  Configure::write('Cache._cake_routes_.duration', '+2 seconds');
}

/*
 * Set the default server timezone. Using UTC makes time calculations / conversions easier.
 * Check http://php.net/manual/en/timezones.php for list of valid timezone strings.
 */
date_default_timezone_set(Configure::read('App.defaultTimezone'));

/*
 * Configure the mbstring extension to use the correct encoding.
 */
mb_internal_encoding(Configure::read('App.encoding'));

/*
 * Set the default locale. This controls how dates, number and currency is
 * formatted and sets the default language to use for translations.
 */
ini_set('intl.default_locale', Configure::read('App.defaultLocale'));

/*
 * Register application error and exception handlers.
 */
$isCli = PHP_SAPI === 'cli';
if ($isCli) {
  (new ErrorTrap(Configure::read('Error')))->register();
  (new ExceptionTrap(Configure::read('Error')))->register();
} else {
  (new ErrorTrap(Configure::read('Error')))->register();
  (new ExceptionTrap(Configure::read('Error')))->register();
}

/*
 * Include the CLI bootstrap overrides.
 */
if ($isCli) {
  require __DIR__ . '/bootstrap_cli.php';
}

/*
 * Set the full base URL.
 * This URL is used as the base of all absolute links.
 */
$fullBaseUrl = Configure::read('App.fullBaseUrl');
if (!$fullBaseUrl) {
  $s = null;
  if (env('HTTPS')) {
    $s = 's';
  }

  $httpHost = env('HTTP_HOST');
  if (isset($httpHost)) {
    $fullBaseUrl = 'http' . $s . '://' . $httpHost;
  }
  unset($httpHost, $s);
}
if ($fullBaseUrl) {
  Router::fullBaseUrl($fullBaseUrl);
}
unset($fullBaseUrl);

Cache::setConfig(Configure::consume('Cache'));
ConnectionManager::setConfig(Configure::consume('Datasources'));
TransportFactory::setConfig(Configure::consume('EmailTransport'));
Mailer::setConfig(Configure::consume('Email'));
Log::setConfig(Configure::consume('Log'));
Security::setSalt(Configure::consume('Security.salt'));

/*
 * Setup detectors for mobile and tablet.
 */
ServerRequest::addDetector('mobile', function ($request) {
  $detector = new \Detection\MobileDetect();

  return $detector->isMobile();
});
ServerRequest::addDetector('tablet', function ($request) {
  $detector = new \Detection\MobileDetect();

  return $detector->isTablet();
});

// Get the API whitelist. If this is empty, all requests will have CORS enabled
$api_whitelist = Configure::read('api-whitelist');

// header('Access-Control-Allow-Methods: POST, GET, PUT, PATCH, DELETE, OPTIONS');
// header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With, Accept, Accept-Encoding, Accept-Language');
// header('Access-Control-Allow-Type: application/json');

if (isset($_SERVER['HTTP_ORIGIN']) && (empty($api_whitelist) || in_array($_SERVER['HTTP_ORIGIN'], $api_whitelist))) {
  header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
} else if (isset($_SERVER['HTTP_REFERER']) && (empty($api_whitelist) || in_array($_SERVER['HTTP_REFERER'], $api_whitelist))) {
  header("Access-Control-Allow-Origin: {$_SERVER['HTTP_REFERER']}");
} else {
  header('Access-Control-Allow-Origin: * ');
}

if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])) {
  header("Access-Control-Allow-Methods: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']}, OPTIONS");
} else {  
  header('Access-Control-Allow-Methods: *');
}

if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])) {
  header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
} else {
  header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With, Accept, Accept-Encoding, Accept-Language');
}

header('Access-Control-Allow-Credentials: true');

if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
  exit(0);
}

/*
 * You can set whether the ORM uses immutable or mutable Time types.
 * The default changed in 4.0 to immutable types. You can uncomment
 * below to switch back to mutable types.
 *
 * You can enable default locale format parsing by adding calls
 * to `useLocaleParser()`. This enables the automatic conversion of
 * locale specific date formats. For details see
 * @link https://book.cakephp.org/4/en/core-libraries/internationalization-and-localization.html#parsing-localized-datetime-data
 */
// TypeFactory::build('time')
//    ->useMutable();
// TypeFactory::build('date')
//    ->useMutable();
// TypeFactory::build('datetime')
//    ->useMutable();
// TypeFactory::build('timestamp')
//    ->useMutable();
// TypeFactory::build('datetimefractional')
//    ->useMutable();
// TypeFactory::build('timestampfractional')
//    ->useMutable();
// TypeFactory::build('datetimetimezone')
//    ->useMutable();
// TypeFactory::build('timestamptimezone')
//    ->useMutable();

/*
 * Custom Inflector rules, can be set to correctly pluralize or singularize
 * table, model, controller names or whatever other string is passed to the
 * inflection functions.
 */
//Inflector::rules('plural', ['/^(inflect)or$/i' => '\1ables']);
Inflector::rules('irregular', [
  'area' => 'aree',
  'progetto' => 'progetti',
  'persona' => 'persone',
  'cliente' => 'clienti',
  'fornitore' => 'fornitori',
  'azienda' => 'aziende',
  'fattura' => 'fatture',
  'ora' => 'ore',
  'notaspesa' => 'notaspese',
  'impiegato' => 'impiegati',
  'fatturaemessa' => 'fattureemesse',
  'fatturaricevuta' => 'fatturericevute',
  'provenienzasoldi' => 'provenienzesoldi',
  'rigafattura' => 'righefatture',
  'rigaddt' => 'righeddt',
  'rigaordine' => 'righeordini',
  'legenda_tipo_impiegato' => 'legenda_tipi_impiegati',
  'vettore' => 'vettori',
  'ordine' => 'ordini',
  'cespite' => 'cespiti',
  'cespitecalendario' => 'cespiticalendario'
]);
Inflector::rules('uninflected', [
  'attivita',
  'legenda_stato_attivita',
  'faseattivita',
  'primanota',
  'legenda_cat_spesa',
  'legenda_mezzi',
  'legenda_porto',
  'legenda_causale_trasporto',
  'legenda_tipo_documento',
  'legenda_tipo_attivita_calendario',
  'legenda_unita_misura',
  'ddt',
]);
