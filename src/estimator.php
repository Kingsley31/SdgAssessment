<?php



function getCurrentlyInfected($reportedCases){
  return $reportedCases * 10; //currentlyInfected
}

function getCurrentlyInfectedForSeverImpact($reportedCases){
  return $reportedCases * 50; //currentlyInfected
}

function getFactor($duration,$periodType){
    $num_of_days=0;
    if($periodType=="days"){
      $num_of_days=$duration;
    }else if($periodType=="weeks"){
      $num_of_days= $duration * 7;
    }else if($periodType=="months"){
      $num_of_days= $duration * 30;
    }
    return intdiv($num_of_days,3);
}

function getInfectionsByRequestedTime($currentlyInfected,$duration,$periodType){
  $factor=getFactor($duration,$periodType);
  $infectionsByRequestedTime=$currentlyInfected * pow(2,$factor);
  return $infectionsByRequestedTime; //infectionsByRequestedTime
}

function getSevereCasesByRequestedTime($infectionsByRequestedTime){
  $severeCasesByRequestedTime=$infectionsByRequestedTime * (15/100);
  return $severeCasesByRequestedTime;//severeCasesByRequestedTime
}


function getImpact($reportedCases,$duration,$periodType){
  $impact=[];

  $currentlyInfected=getCurrentlyInfected($reportedCases);
  $impact["currentlyInfected"]=$currentlyInfected;

  $infectionsByRequestedTime=getInfectionsByRequestedTime($currentlyInfected,$duration,$periodType);
  $impact["infectionsByRequestedTime"]=$infectionsByRequestedTime;

  $severeCasesByRequestedTime=getSevereCasesByRequestedTime($infectionsByRequestedTime);
  $impact["severeCasesByRequestedTime"]=$severeCasesByRequestedTime;

  return $impact;
}

function getSeverImpact($reportedCases,$duration,$periodType){
  $severeImpact=[];
  $currentlyInfected_severe=getCurrentlyInfectedForSeverImpact($reportedCases);
  $severeImpact["currentlyInfected"]=$currentlyInfected_severe;

  $infectionsByRequestedTime_severe=getInfectionsByRequestedTime($currentlyInfected_severe,$duration,$periodType);
  $severeImpact["infectionsByRequestedTime"]=$infectionsByRequestedTime_severe;

  $severeCasesByRequestedTime_severe=getSevereCasesByRequestedTime($infectionsByRequestedTime_severe);
  $severeImpact["severeCasesByRequestedTime"]=$severeCasesByRequestedTime_severe;

  return $severeImpact;
}


function covid19ImpactEstimator($data)
{
  
  //Data Needed functions
  $reportedCases=$data["reportedCases"];
  $duration=$data["timeToElapse"];
  $periodType=$data["periodType"];

 
  $output["data"] =$data;
  $output["impact"]=getImpact($reportedCases,$duration,$periodType);
  $output["severeImpact"]=getSeverImpact($reportedCases,$duration,$periodType);
  $data=$output;
  
  return $data;
}


