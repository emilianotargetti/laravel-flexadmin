<?php

// File: emilianotargetti//flexasset/src/Helpers/FlexAssetManager.php

namespace EmilianoTargetti\FlexAsset\Helpers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Config;

/**
 * Gestisce l'aggiunta e la gestione dinamica degli asset (CSS e JS)
 * per diverse aree dell'applicazione.
 *
 * Questa classe carica automaticamente gli asset configurati dal file
 * 'config/flexasset.php' e permette l'aggiunta di nuovi asset
 * durante l'esecuzione dell'applicazione.
 */
class FlexAssetManager
{
    /**
     * Il percorso di base relativo alla cartella 'public' per tutti gli asset.
     *
     * @var string
     */
    protected $basePath;

    /**
     * Un array associativo per memorizzare i percorsi dei file CSS.
     * Gli asset sono raggruppati per area (es. 'common', 'default', 'admin').
     *
     * @var array
     */
    protected $css = [
        'common' => [],
        'default' => [],
        'admin' => [],
    ];

    /**
     * Un array associativo per memorizzare i percorsi dei file JavaScript.
     * Gli asset sono raggruppati per area (es. 'common', 'default', 'admin').
     *
     * @var array
     */
    protected $js = [
        'common' => [],
        'default' => [],
        'admin' => [],
    ];

    /**
     * Un array per memorizzare i percorsi degli asset non trovati.
     *
     * @var array
     */
    protected $errors = [];

    /**
     * Costruttore della classe.
     * Inizializza il percorso base e carica automaticamente gli asset
     * definiti nel file di configurazione 'flexasset.php'.
     */
    public function __construct()
    {
        $this->basePath = config('flexasset.base_path', 'assets');
        $aree = array_keys(config('flexasset.aree', []));

        if (!empty($aree)) {
            foreach ($aree as $area) {
                // Aggiunge i file CSS configurati per l'area corrente.
                foreach (config('flexasset.aree.' . $area . '.css', []) as $path) {
                    $this->addCss($path, $area);
                }

                // Aggiunge i file JavaScript configurati per l'area corrente.
                foreach (config('flexasset.aree.' . $area . '.js', []) as $path) {
                    $this->addJs($path, $area);
                }
            }
        }
    }

    /**
     * Aggiunge un file CSS al gestore degli asset.
     * Se il percorso non è un URL, viene validato e convertito in un percorso assoluto.
     *
     * @param string $path Il percorso del file CSS (relativo al basePath o un URL completo).
     * @param string $area L'area di destinazione per l'asset (es. 'default', 'admin', 'common').
     * @return void
     */
    public function addCss(string $path, $area = 'default'): void
    {
        $path = trim($path);
        $area = !empty(trim($area)) && strlen(trim($area) > 0) ? trim($area) : 'default';

        if (!isset($this->css[$area])) {
            $this->css[$area] = [];
        }

        if (!empty($path)) {
            if (!$this->isUrl($path)) {
                $fullPath = public_path($this->basePath . '/' . $path);
                if (file_exists($fullPath)) {
                    $path = asset($this->basePath . '/' . $path);
                } else {
                    $this->errors[] = $path;
                    $path = null;
                }
            }

            if (!is_null($path) && !in_array($path, $this->css[$area])) {
                $this->css[$area][] = $path;
            }
        }
    }

    /**
     * Aggiunge un file JavaScript al gestore degli asset.
     * Se il percorso non è un URL, viene validato e convertito in un percorso assoluto.
     *
     * @param string $path Il percorso del file JS (relativo al basePath o un URL completo).
     * @param string $area L'area di destinazione per l'asset (es. 'default', 'admin', 'common').
     * @return void
     */
    public function addJs(string $path, $area = 'default'): void
    {
        $path = trim($path);
        $area = !empty(trim($area)) && strlen(trim($area) > 0) ? trim($area) : 'default';

        if (!isset($this->js[$area])) {
            $this->js[$area] = [];
        }

        if (!empty($path)) {
            if (!$this->isUrl($path)) {
                $fullPath = public_path($this->basePath . '/' . $path);
                if (file_exists($fullPath)) {
                    $path = asset($this->basePath . '/' . $path);
                } else {
                    $this->errors[] = $path;
                    $path = null;
                }
            }

            if (!is_null($path) && !in_array($path, $this->js[$area])) {
                $this->js[$area][] = $path;
            }
        }
    }

    /**
     * Restituisce l'array dei percorsi CSS per l'area specificata.
     * Per le aree 'default' e 'admin', vengono uniti anche gli asset dell'area 'common'.
     *
     * @param string $area L'area da cui recuperare gli asset (es. 'default', 'admin', 'common').
     * @return array
     */
    public function getCss($area = 'default'): array
    {
        $aree = array_keys(config('flexasset.aree', []));
        $area = !empty(trim($area)) && strlen(trim($area) > 0) ? trim($area) : null;

        if (!is_null($area) && in_array($area, ['default', 'admin'])) {
            return array_merge($this->css['common'], $this->css[$area]);
        }

        if (!is_null($area) && in_array($area, $aree)) {
            return $this->css[$area];
        }

        return []; // Restituisce un array vuoto se l'area non esiste
    }

    /**
     * Restituisce l'array dei percorsi JavaScript per l'area specificata.
     * Per le aree 'default' e 'admin', vengono uniti anche gli asset dell'area 'common'.
     *
     * @param string $area L'area da cui recuperare gli asset (es. 'default', 'admin', 'common').
     * @return array
     */
    public function getJs($area = 'default'): array
    {
        $aree = array_keys(config('flexasset.aree', []));
        $area = !empty(trim($area)) && strlen(trim($area) > 0) ? trim($area) : null;

        if (!is_null($area) && in_array($area, ['default', 'admin'])) {
            return array_merge($this->js['common'], $this->js[$area]);
        }

        if (!is_null($area) && in_array($area, $aree)) {
            return $this->js[$area];
        }

        return []; // Restituisce un array vuoto se l'area non esiste
    }

    /**
     * Restituisce un array contenente i percorsi degli asset non trovati.
     *
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Esegue il dump dello stato corrente dell'istanza della classe FlexAssetManager
     * e termina l'esecuzione dello script.
     * Utile per il debug.
     *
     * @return void
     */
    public function dd_facade(): void
    {
        dd($this);
    }

    /**
     * Verifica se una data stringa è un URL valido.
     *
     * @param string $path La stringa da verificare.
     * @return bool
     */
    private function isUrl($path): bool
    {
        $validator = Validator::make(['url' => $path], ['url' => 'url']);
        return !$validator->fails();
    }
}
