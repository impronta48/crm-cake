# conTrasporto

## WhatsApp

Lo schema architetturale dell'integrazone con WhatsApp è riportato in `conTrasposto.drawio.png`

### Configurazione

#### Crm-Vue

In .env

//API
API_URL = https://l.crm.bikesquare.eu/api

//Chat Web Socket
WS_URL = ws://localhost:5001
WS_AUTH_TOKEN = bb0M8F9xRLxaFRr1g4MTS4rkczuJAR8d7GO52pc7vbBFvHj8QRjSY1uURwy6GPGw

#### Crm-Cake

In app_local.php

'WhatsApp' => [
    'base_url' => 'http://localhost',
    'port' => 4000,
    'api_key' => 'a6bc226axxxxxxxxxxxxxx',
    'webhook' => 'http://freescout.drupalvm.test/whatsapp/webhook/1/185843707/3',
],

'WebSocket' => [
    'base_url' => 'ws://localhost',
    'port' => 5001,
    'vue_api_key' => 'bb0M8F9xRLxaFRr1g4MTS4rkczuJAR8d7GO52pc7vbBFvHj8QRjSY1uURwy6GPGw',
    'cake_api_key' => 'KieCiqCcSzzE0LDmQisPKEj1pXDTxq6cUVPGyXXvKxfAlJTTvTsZ03DCPE1IJX2W',
],

#### baileys-api-2.0.0

In .env
PORT="4000"
NODE_ENV="development"
URL_WEBHOOK="https://l.crm.bikesquare.eu/api/whatsapp/receive.json"
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

#### Database
Eseguire:
- `bin/cake migrations migrate`
- `bin/cake cache clear_all`

### Collegamento WebSocket
Il collegamento WebSocket viene implementato dalla libreria `ratchet/pawl`
Ad oggi, 17/03/2024, risulta esserci un bug di retrocompatibilità nella versione `0.4.2`.
Fino alla risoluzione del problema, fissiamo l'uso della sola versione `0.4.1`, come riportato sotto:
`"ratchet/pawl": "0.4.1",`

Per far partire il WebSocket chat server che gestisce la chat WhatsApp, eseguire:
`bin/cake WebSocketChatServer`


## Campagne

Le campagne possono essere create via mail o via WhatsApp.
L'invio effettivo viene eseguito da due Command, che devono essere configurati nel CronTab di crm-cake.
I due command sono:
- `bin/cake EmailQueue.sender -h`
- `bin/cake WhatsappSender`





