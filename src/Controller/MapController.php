<?php

namespace App\Controller;

use App\Service\MapService;
use App\Service\MapGeneratorService;
use App\Config\GameConfig;
use DateTime;

class MapController extends DefaultController
{

    public function generateOreMap(){
        $mapGeneratorService = new MapGeneratorService;
        $mapGeneratorService->generateOreMap();
    }

    public function readMap(){
        $mapService = new MapService;
        $map = $mapService->readMap();

        $gameConfig = new GameConfig;
        $config = $gameConfig->getOreMapSettings();

        $result = [$map, $config];
        echo json_encode($result);
    }

}