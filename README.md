# laravel-flexadmin
A Laravel package for dynamic asset management, inspired by FuelPHP's Asset class. It allows you to add and group CSS and JS files by area, ensuring a clean, flexible, and organized approach to managing your web assets.

FlexAsset: Gestore di Asset per Laravel
FlexAsset è un pacchetto per Laravel che offre una soluzione flessibile e potente per la gestione degli asset (CSS e JavaScript). Ispirato alla classe Asset di FuelPHP, permette di aggiungere, raggruppare e rendere gli asset in modo dinamico, con supporto per diverse "aree" dell'applicazione come default, admin e altre personalizzate.

Caratteristiche Principali
Gestione dinamica: Aggiungi asset in qualsiasi punto della tua applicazione (controller, viste, middleware).

Raggruppamento per aree: Organizza gli asset in aree specifiche, facilitando la gestione di layout diversi.

Supporto a percorsi locali e URL esterni: Riconosce e gestisce sia i file locali che i link esterni.

Direttive Blade: Rendi gli asset direttamente nelle tue viste con direttive Blade pulite e intuitive.

Configurazione flessibile: Personalizza percorsi e asset predefiniti tramite un file di configurazione dedicato.

Installazione
Puoi installare il pacchetto tramite Composer.

Bash

composer require emilianotargetti/flexasset
Dopo l'installazione, il pacchetto sarà automaticamente scoperto da Laravel.

Per pubblicare il file di configurazione, esegui il seguente comando Artisan:

Bash

php artisan vendor:publish --tag=flexasset-config
Questo comando copierà il file flexasset.php nella cartella config/ del tuo progetto.

Configurazione
Il file di configurazione pubblicato config/flexasset.php ti permette di personalizzare il comportamento del pacchetto.

PHP

return [
    /*
     * Il percorso di base relativo alla cartella 'public'.
     */
    'base_path' => 'assets',

    /*
     * Gli asset che devono essere caricati automaticamente per ogni area.
     * I file in 'common' vengono inclusi in 'default' e 'admin'.
     */
    'aree' => [
        'common' => [
            'css' => [
                'css/reset.css',
            ],
            'js' => [
                'js/jquery.min.js',
            ],
        ],
        'default' => [
            'css' => [
                'css/app.css',
            ],
            'js' => [
                'js/app.js',
            ],
        ],
        'admin' => [
            'css' => [
                'css/admin.css',
            ],
            'js' => [
                'js/admin.js',
            ],
        ],
        'blog' => [
            'css' => [
                'css/blog.css',
            ],
            'js' => [
                'js/blog.js',
            ],
        ],
    ],
];
Utilizzo
Aggiungere Asset
Puoi aggiungere asset utilizzando il Facade FlexAsset in qualsiasi punto della tua applicazione.

PHP

use FlexAsset;

// Aggiunge un file CSS all'area 'default'
FlexAsset::addCss('css/styles.css');

// Aggiunge un file JavaScript all'area 'admin'
FlexAsset::addJs('js/scripts.js', 'admin');

// Aggiunge un file CSS da un URL esterno
FlexAsset::addCss('https://unpkg.com/leaflet@1.7.1/dist/leaflet.css');
Rendering degli Asset in Blade
Per stampare gli asset nelle tue viste, usa le direttive Blade dedicate.

Blade

<!DOCTYPE html>
<html lang="en">
<head>
    @flexasset_css('default')
</head>
<body>
    @flexasset_js('default')
</body>
</html>
Il pacchetto offre due direttive Blade:

@flexasset_css($area): Rende i tag <link> per i file CSS.

@flexasset_js($area): Rende i tag <script> per i file JavaScript.

Se non specifichi un'area, verrà utilizzata l'area default.

Contribuire
Sentiti libero di inviare pull request, segnalare bug o suggerire nuove funzionalità.
Sei il benvenuto a contribuire al progetto!

Licenza
Questo pacchetto è un software open-source con licenza MIT.
