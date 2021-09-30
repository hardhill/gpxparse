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

$gpx->SetSmooth(true);
echo "=============== smooth ================".PHP_EOL;
echo "Start at ->".$gpx->GetTrack()->stats->startedAt->format('H:i:s').PHP_EOL;
echo "Finish at ->".$gpx->GetTrack()->stats->finishedAt->format('H:i:s').PHP_EOL;
echo "Duration: ".GPXStart::secondsToTime($gpx->GetTrack()->stats->duration).PHP_EOL;
echo "Distance: ".$gpx->GetTrack()->stats->distance.PHP_EOL;
$gpx->SetSmooth(false);

$points = $gpx->GetTrack()->getPoints();

echo "Time move: ".GPXStart::secondsToTime($gpx->TimeMove()).PHP_EOL;

//$n = 0;
//for($i=0;$i<count($points);$i++){
////    echo $i.PHP_EOL;
////    echo $points[$i]->time->format('H:i:s').PHP_EOL;
//    //------------- calc distance ---------------
//
//    if($i<count($points)-1){
//       $dist = DistanceCalculator::calculate([$points[$i],$points[$i+1]]);
//       if($dist==0.0){
//
//           echo ($i+1)."| Dist minimum ".$dist."\t\t|".$points[$i+1]->time->format('H:i:s')."\t".$n++.PHP_EOL;
//       }
//    }
//}





