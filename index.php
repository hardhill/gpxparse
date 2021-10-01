<?php

use Classes\GPXStart;
use phpGPX\Helpers\DistanceCalculator;

require_once('Classes/GPXStart.php');





$filename = 'H:\\GPX\\walk.gpx';
$gpx = new GPXStart($filename);

echo $gpx->GetTitle().PHP_EOL;
echo "Старт:\t",$gpx->GetTimeStart()->format('Y-m-d H:i:s').PHP_EOL;
echo $gpx->GetTrack()->type.PHP_EOL;
echo $gpx->GetFile()->creator.PHP_EOL;

echo $gpx->GetIdTrack().PHP_EOL;
echo "Start at ->".$gpx->GetTrack()->stats->startedAt->format('H:i:s').PHP_EOL;
echo "Finish at ->".$gpx->GetTrack()->stats->finishedAt->format('H:i:s').PHP_EOL;
echo "Duration: ".GPXStart::secondsToTime($gpx->GetTrack()->stats->duration).PHP_EOL;
echo "Distance: ".$gpx->GetTrack()->stats->distance.PHP_EOL;
echo "Pace: ".$gpx->GetStat()->averagePace." or ".$gpx::secondsToTime($gpx->GetStat()->averagePace).PHP_EOL;

$gpx->SetSmooth(true);
echo "=============== smooth ================".PHP_EOL;
echo "Start at ->".$gpx->GetTrack()->stats->startedAt->format('H:i:s').PHP_EOL;
echo "Finish at ->".$gpx->GetTrack()->stats->finishedAt->format('H:i:s').PHP_EOL;
echo "Duration: ".GPXStart::secondsToTime($gpx->GetTrack()->stats->duration).PHP_EOL;
echo "Distance: ".$gpx->GetTrack()->stats->distance.PHP_EOL;
$gpx->SetSmooth(false);

$points = $gpx->GetTrack()->getPoints();

echo "Moving time: ".GPXStart::secondsToTime($gpx->MovingTime()).PHP_EOL;
$avrpace = $gpx::secondsToTime($gpx->MovingTime()*1000/$gpx->GetStat()->distance);
echo "Average pace: ".$gpx->AveragePaceMT()." or ".$avrpace.PHP_EOL;






