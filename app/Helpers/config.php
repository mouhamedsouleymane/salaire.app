<?php 


namespace App\Helpers;

use PhpParser\Builder\ClassConst;

use App\Models\Configuration;

Class Config {

    public static function getAppName() {
        $appName = Configuration::where('type', 'APP_NAME')->value('value');
        return $appName;
        
    }
} 