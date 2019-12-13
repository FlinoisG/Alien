<?php 
require_once "vendor/autoload.php";

use App\Controller\sqlQueryService;
use App\Service\MapGeneratorService;
use App\Service\MapService;
use App\Service\PerlinService;
use App\Config\GameConfig;

$mapGeneratorService = new MapGeneratorService;
$mapService = new MapService;
$perlinService = new PerlinService;


$mapGeneratorService->generateOreMap();


?>