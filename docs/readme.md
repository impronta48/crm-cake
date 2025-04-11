# conTrasporto

## Architettura integrazione WhatsApp

Lo schema architetturale dell'integrazone con WhatsApp è riportato in `conTrasposto.drawio.png`

## Note di installazione

### Crm-Vue

Installare node22.14
Per un bug nel modulo corepack di Node [Newly published versions of package managers distributed from npm cannot be installed due to key id mismatch. https://github.com/nodejs/corepack/issues/612], con la versione precedente non si riesce ad installare il nuxt Telegram Auth (https://halitsever.github.io/nuxt-telegram-auth/installation.html)

Nuxt non poarte più a causa di questo errore:
https://github.com/primefaces/primevue/issues/7434
provo a lanciare npm install @primevue/forms -> OK, questo risolve

## Configurazione

### Crm-Vue

In .env

//API
API_URL = https://l.crm.bikesquare.eu/api
API_KEY = {valore configurato su crm-cake} // da verificare se serve ancora

// Freescout
FREESCOUT_URL = http://freescout.drupalvm.test/

// Telegram bot (per il login)
TELEGRAM_BOT = contrasporto_ridotta_bot
TELEGRAM_TOKEN = 7876209700:AAFi_n3nsRGq6fgGJpQTieUicpZqb85Sk1U

//Chat Web Socket
WS_URL = ws://localhost:5001
WS_AUTH_TOKEN = bb0M8F9xRLxaFRr1g4MTS4rkczuJAR8d7GO52pc7vbBFvHj8QRjSY1uURwy6GPGw

### Crm-Cake

Settare in ./sites/default/migration.php il nome del progetto (necessario per pilotare correttamente le migrazioni, che vanno customizzate progetto per progetto, in modo da arrivare ad avere un DB compatibile con CRM)

Eseguire la migrazione riportata in https://github.com/impronta48/cakephp-email-queue

In app_local.php

 'App' => [
    'base' => '/api',
    'webroot' => 'webroot',
    'fullBaseUrl' => 'https://l.crm.bikesquare.eu',
    'imageBaseUrl' => 'img/',
    'key' => 'R6PDl3A6lgvjV9UB0tMxbwoRHqbKSVo4', //api key per le chiamate server-to-server (da baileys e freescout)
  ],

'WhatsApp' => [
    'base_url' => 'http://localhost',
    'port' => 4000,
    'api_key' => 'a6bc226axxxxxxxxxxxxxx', // valore API_KEY nel .env di baileys
    'freescout_webhook' => 'http://freescout.drupalvm.test/whatsapp/webhook/1/3503896588/3', // riportato nella pagina di  configurazione di freescout
],

'WebSocket' => [
    'base_url' => 'ws://localhost',
    'port' => 5001,
    'vue_api_key' => 'bb0M8F9xRLxaFRr1g4MTS4rkczuJAR8d7GO52pc7vbBFvHj8QRjSY1uURwy6GPGw',
    'cake_api_key' => 'KieCiqCcSzzE0LDmQisPKEj1pXDTxq6cUVPGyXXvKxfAlJTTvTsZ03DCPE1IJX2W',
],

### Collegamento WebSocket

Per far partire il WebSocket chat server che gestisce la chat WhatsApp, eseguire:
`bin/cake WebSocketChatServer`

### Campagne
Le campagne possono essere create via mail o via WhatsApp.
L'invio effettivo viene eseguito da due Command, che devono essere configurati nel CronTab di crm-cake.
I due command sono:
- `bin/cake EmailQueue.sender -h`
- `bin/cake WhatsappSender`

### baileys-api-2.0.0

Creazione del DB baileys_api:
npx prisma migrate (dev|deploy)

In .env
PORT="4000"
NODE_ENV="development"
URL_WEBHOOK="https://l.crm.bikesquare.eu/api/whatsapp/receive.json?crm-api-key={{valore configurato in crm-cake App.key}}"
ENABLE_WEBHOOK="true"
ENABLE_WEBSOCKET="true"
BOT_NAME="Whatsapp Bot"
DATABASE_URL="mysql://root:@localhost:3306/baileys_api"
LOG_LEVEL="debug"
RECONNECT_INTERVAL="5000"
MAX_RECONNECT_RETRIES="5"
SSE_MAX_QR_GENERATION="10"
SESSION_CONFIG_ID="session-config"
API_KEY="a6bc226axxxxxxxxxxxxxx"

### freescout

CRM_BASE_URL=https://l.crm.bikesquare.eu/api
CRM_API_KEY = {{valore configurato in crm-cake App.key}}
CRM_SSL=false
APP_CURL_SSL_VERIFYPEER = false

### Database
Eseguire:
- `bin/cake migrations migrate`
- `bin/cake cache clear_all`


## Collegamento WebSocket
Il collegamento WebSocket viene implementato dalla libreria `ratchet/pawl`
Ad oggi, 17/03/2024, risulta esserci un bug di retrocompatibilità nella versione `0.4.2`.
Fino alla risoluzione del problema, fissiamo l'uso della sola versione `0.4.1`, come riportato sotto:
`"ratchet/pawl": "0.4.1",`


