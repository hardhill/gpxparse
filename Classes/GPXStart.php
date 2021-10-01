<?php

namespace Classes;

require_once('vendor/autoload.php');

use phpGPX\Helpers\GeoHelper;
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
    // время в движении
    public function MovingTime():int
    {
        $timemove = 0;
        $points = $this->track->getPoints();
        $pointCount = count($points);
        $lastConsideredPoint = null;
        for ($p = 0; $p < $pointCount; $p++) {
            $curPoint = $points[$p];

            // skip the first point
            if ($p === 0) {
                $lastConsideredPoint = $curPoint;
                continue;
            }
            // calculate the delta from current point to last considered point
            //$curPoint->difference = GeoHelper::getDistance($lastConsideredPoint, $curPoint);
            $timedifference = $curPoint->time->getTimestamp() - $lastConsideredPoint->time->getTimestamp();
            $pace = 0;
            if($curPoint->difference>0){
                $pace = $timedifference/$curPoint->difference;
                if($pace < 3.0){
                   // echo $pace.PHP_EOL;
                    $timemove = $timemove + $timedifference;
                }
            }

            $lastConsideredPoint = $curPoint;
        }
        return $timemove;
    }

    public static function secondsToTime($seconds_time)
    {
        if ($seconds_time < 24 * 60 * 60) {
            return gmdate('H:i:s', $seconds_time);
        } else {
            $hours = floor($seconds_time / 3600);
            $minutes = floor(($seconds_time - $hours * 3600) / 60);
            $seconds = floor($seconds_time - ($hours * 3600) - ($minutes * 60));
            return "$hours:$minutes:$seconds";
        }
    }

    public function AveragePaceMT()
    {
        return ($this->MovingTime()*1000)/$this->GetStat()->distance;
    }

}