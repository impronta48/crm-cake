<?php
// Inserire qui un modello di configurazione di esempio per il file settings.php
// Aggiornare ogni volta che si aggiunge una variabile di configurazione
// Aggiungere anche in app.php la variabile di configurazione di default (in modo che se non c'è qui venga usata quella)
// Questo file è su github pubblico 
// *** NON INSERIRE DATI SENSIBILI ***
return [

    'App' => [
      'base' => '/api',
      'webroot' => '/path/to/webroot/',
      'fullBaseUrl' => 'https://example.com',
      'imageBaseUrl' => 'img/',
    ],
    /*
       * Debug Level:
       *
       * Production Mode:
       * false: No error messages, errors, or warnings shown.
       *
       * Development Mode:
       * true: Errors and warnings shown.
       */
    'debug' => filter_var(env('DEBUG', true), FILTER_VALIDATE_BOOLEAN),
  
    /*
       * Security and encryption configuration
       *
       * - salt - A random string used in security hashing methods.
       *   The salt value is also used as the encryption key.
       *   You should treat it as extremely sensitive data.
       */
    'Security' => [
      'salt' => env('SECURITY_SALT', 'your_security_salt_here'),
    ],
  
    /*
       * Connection information used by the ORM to connect
       * to your application's datastores.
       *
       * See app.php for more configuration options.
       */
    'Datasources' => [
      'default' => [
        'host' => 'localhost',
        /*
               * CakePHP will use the default DB port based on the driver selected
               * MySQL on MAMP uses port 8889, MAMP users will want to uncomment
               * the following line and set the port accordingly
               */
        //'port' => 'non_standard_port_number',
  
        'username' => 'your_db_username',
        'password' => 'your_db_password',
  
        'database' => 'your_database_name',
        /*
               * If not using the default 'public' schema with the PostgreSQL driver
               * set it here.
               */
        //'schema' => 'myapp',
  
        /*
               * You can use a DSN string to set the entire configuration
               */
        'url' => env('DATABASE_URL', null),
      ],
  
      /*
           * The test connection is used during the test suite.
           */
      'test' => [
        'host' => 'localhost',
        //'port' => 'non_standard_port_number',
        'username' => 'your_test_db_username',
        'password' => 'your_test_db_password',
        'database' => 'your_test_database_name',
        //'schema' => 'myapp',
        'url' => env('DATABASE_TEST_URL', null),
      ],
    ],
  
    /*
       * Email configuration.
       *
       * Host and credential configuration in case you are using SmtpTransport
       *
       * See app.php for more configuration options.
       */
    'EmailTransport' => [
      'Debug' => [
        'className' => 'Debug',
      ],
      'default' => [
        'host' => 'localhost',
        'port' => 25,
        'username' => null,
        'password' => null,
        'client' => null,
        'url' => env('EMAIL_TRANSPORT_DEFAULT_URL', null),
      ],
    ],
  
    /*
    * WhatsApp Configuration.
    */
    'WhatsApp' => [
      'base_url' => 'http://localhost',
      'port' => 4000,
      'api_key' => 'your_whatsapp_api_key',
    ],
  
    /*
     * WebSocket Configuration.
     */
    'WebSocket' => [
      'base_url' => 'ws://localhost',
      'port' => 5001,
      'vue_api_key' => 'your_vue_api_key',
      'cake_api_key' => 'your_cake_api_key',
    ],
  
    /*
     * Campaigns Configuration.
     */  
  //   'urlPrenota' => 'http://prenota.example.test',
  //   'logo' => 'https://example.com/img/logo.svg',
  //   'MailAdmin' => ['info@example.com' => 'Example'],
    'MailLogo' => "/img/logo.png",
  //   'Trello.sales' => "https://trello.com/b/example/example-sales-pipeline",
  //   'DefaultMessage' => <<<'EOT'
  //     Dear :Name,
  //     We are pleased to invite you to an important event that will take place
  //     on <b>June 5, 2020</b> at 6 PM at our headquarters.
  
  //     These are some available fields:
  //     :Name
  //     :Surname
  //     :DisplayName
  //     :City
  //     :Province
  //     :Country
  //     :Title
  //     :Position
  //     :Company
  
  //     Best regards,
  //     The team
  //   EOT,
  
  ];