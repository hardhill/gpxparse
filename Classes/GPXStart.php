<?php

namespace Classes;

require_once('vendor/autoload.php');

use phpGPX\Models\Stats;
use phpGPX\Models\Track;
use phpGPX\phpGPX;

class GPXStart extends phpGPX
{
    private $filename;
    private $strgpx = '';
    private $gpx;
    private $file;
    private $track;
    /**
     * @param string $filename
     */
    public function __construct($filename)
    {

        $this->filename = $filename;
        $this->gpx = new phpGPX();
        $this->file = $this->gpx->load($filename);
        $this->track = $this->file->tracks[0];
    }

    public function GetFile():\phpGPX\Models\GpxFile {
        try {
            return $this->file;
        }catch (\Exception $err){
            echo $err;
        }
        return $this->file;
    }
    // первый трэк в файле
    public function GetTrack():Track{
        return $this->track;
    }
    //заголовок трека
    public function GetTitle():string{
        return $this->track->name;
    }
    //содержимое файла в виде строки
    public function GetContent():string{
        try {
            $s = file_get_contents($this->filename);
        }catch(\Exception $err){

        }
        return $s;
    }

    // время начала записи файла
    public function GetTimeStart():\DateTime{
        return $this->file->metadata->time;
    }
    // хэш функция содержимого файла GPX
    public function GetHashGPX():string{
        return hash('sha512',$this->GetContent());
    }

    public function GetIdTrack():string{
        $points = $this->track->getPoints();
        $acc = "";
        for($i=0;$i<5;$i++){
            $point = $points[$i];
            $acc = $acc.$point->time->format('c').$point->latitude.$point->longitude."\n";
        }
        return hash('md5',$acc);
    }
    public function GetStat():Stats{
        return $this->track->stats;
    }


    public function SetSmooth(bool $smooth)
    {
        self::$APPLY_DISTANCE_SMOOTHING = $smooth;
        $this->GetTrack()->recalculateStats();
    }


}