<?php

namespace App\Service;

use App\Model\Service;
use App\Controller\sqlQueryService;
use App\Service\MapGeneratorService;
use App\Service\PerlinService;
use App\Config\GameConfig;

class MapService extends Service
{
    public function readMap()
    {
        $gameConfig = new GameConfig;
        $oreMapSettings = $gameConfig->getOreMapSettings();
        $oreMapFileLocation = $gameConfig->getOreMapFileLocation();
    
        $handle = fopen($oreMapFileLocation, "r");
        $oreMap = fread($handle, filesize($oreMapFileLocation));
        fclose($handle);

        $oreMap = json_decode($oreMap, true);
        
        return $oreMap;
    }
}