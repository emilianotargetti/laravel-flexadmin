<?php

// File: emilianotargetti//flexasset/src/Facades/FlexAsset.php

namespace EmilianoTargetti\FlexAsset\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \App\Managers\FlexAssetManager
 */
class FlexAsset extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'flexasset';
    }
}
