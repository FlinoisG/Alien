<?php

namespace App\Config;

class GameConfig
{

    /////////////////////////////
    //  Ore Map Generation Settings
    /////////////////////////////

    protected $gridSizeX = 64; // lat
    protected $gridSizeY = 128; // long

    protected $ironNodeFreq = 10; // higher value = lower freq but bigger node
    protected $ironNodeSize = 0.3; // 0 = max  1 = min

    protected $copperNodeFreq = 8; // higher value = lower freq but bigger node
    protected $copperNodeSize = 0.25; // 0 = max  1 = min

    protected $oreMapFileLocation = "oreMap.json";

    public function getOreMapSettings()
    {
        return [
            "gridSizeX"=>$this->gridSizeX, 
            "gridSizeY"=>$this->gridSizeY, 
            "ironNodeFreq"=>$this->ironNodeFreq, 
            "ironNodeSize"=>$this->ironNodeSize,
            "copperNodeFreq"=>$this->copperNodeFreq, 
            "copperNodeSize"=>$this->copperNodeSize
        ];
    }

    public function getOreMapFileLocation()
    {
        return $this->oreMapFileLocation;
    }

}