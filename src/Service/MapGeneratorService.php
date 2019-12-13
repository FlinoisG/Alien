<?php

namespace App\Service;

use App\Model\Service;
use App\Service\PerlinService;
use App\Config\GameConfig;

class MapGeneratorService extends Service
{


    /**
     * Generate a new ore map into deposit/Maps/oreMap.json
     *
     * @return void
     */
    public function generateOreMap()
    {
        
        $gameConfig = new GameConfig;
        $oreMapSettings = $gameConfig->getOreMapSettings();

        $gridSizeX = $oreMapSettings["gridSizeX"];
        $gridSizeY = $oreMapSettings["gridSizeY"];
        $ironNodeFreq = $oreMapSettings["ironNodeFreq"];
        $ironNodeSize = $oreMapSettings["ironNodeSize"];
        $copperNodeFreq = $oreMapSettings["copperNodeFreq"];
        $copperNodeSize = $oreMapSettings["copperNodeSize"];
        $ironNodeSize = $ironNodeSize * -1;
        $ironNodeSize = $ironNodeSize + 1;
        $copperNodeSize = $copperNodeSize * -1;
        $copperNodeSize = $copperNodeSize + 1;
        
        $copperLayer = $this->generateOreLayer($gridSizeX, $gridSizeY, $copperNodeFreq, $copperNodeSize, "copper");
        $content = $this->generateOreLayer($gridSizeX, $gridSizeY, $ironNodeFreq, $ironNodeSize, "iron", $copperLayer);

        $oreMapFileLocation = $gameConfig->getOreMapFileLocation();
        $contentString = json_encode($content);
        $fp = fopen($oreMapFileLocation, 'w');
        fwrite($fp, $contentString);
        fclose($fp);
    }


    public function generateOreLayer($gridSizeX, $gridSizeY, $nodeFreq, $nodeSize, $type, $layer = []){
        $perlinService = new PerlinService;
        
        for($y=0; $y<$gridSizeY; $y+=1) {
            for($x=0; $x<$gridSizeX; $x+=1) {
                if ($y % 2 != 0) {
                    $fakeY = $y - 1;
                } else {
                    $fakeY = $y;
                }
                if ($x % 2 != 0) {
                    $fakeX = $x - 1;
                } else {
                    $fakeX = $x;
                }

                $num = $perlinService->noise($x,$y,0,$nodeFreq);                
                $raw = ($num/2)+.5;
                if ($raw < 0) $raw = 0;                
                $num = dechex( $raw*255 );                
                if (strlen($num) < 2) $num = "0".$num;                
                if ($raw > $nodeSize){
                    if ($raw > 1) $raw = 1; 
                    $min = $nodeSize;
                    $max = 1;
                    $normalized = ($raw-$min) / ($max-$min);
                    $layer[$x][$y] = ["value" => $normalized, "type" => $type];  
                }            
            }
        }

        return $layer;
    
    }

}

