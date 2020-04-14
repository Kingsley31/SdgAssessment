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

function getHospitalBedsByRequestedTime($totalHospitalBeds,$severeCasesByRequestedTime){
     return intval(($totalHospitalBeds * (35/100)) - $severeCasesByRequestedTime);
} 

function getCasesForICUForICUByRequestedTime($infectionsByRequestedTime){
   return $infectionsByRequestedTime * (5/100);
}


function getCasesForVentilatorsByRequestedTime($infectionsByRequestedTime){
  return $infectionsByRequestedTime * (2/100);
}

function getNumOfDays($duration,$periodType){
    $num_of_days=0;
    if($periodType=="days"){
      $num_of_days=$duration;
    }else if($periodType=="weeks"){
      $num_of_days= $duration * 7;
    }else if($periodType=="months"){
      $num_of_days= $duration * 30;
    }
    return $num_of_days;
}

function getDollarsInFlight($infectionsByRequestedTime,$duration,$periodType,$avgDailyIncomeInUSD,$avgDailyIncomePopulation){
    $num_of_days=getNumOfDays($duration,$periodType);
    return intdiv(($infectionsByRequestedTime * $avgDailyIncomePopulation * $avgDailyIncomeInUSD), $num_of_days);
}


function getImpact($reportedCases,$duration,$periodType,$totalHospitalBeds,$avgDailyIncomeInUSD,$avgDailyIncomePopulation){
  $impact=[];

  $currentlyInfected=getCurrentlyInfected($reportedCases);
  $impact["currentlyInfected"]=$currentlyInfected;

  $infectionsByRequestedTime=getInfectionsByRequestedTime($currentlyInfected,$duration,$periodType);
  $impact["infectionsByRequestedTime"]=$infectionsByRequestedTime;

  $severeCasesByRequestedTime=getSevereCasesByRequestedTime($infectionsByRequestedTime);
  $impact["severeCasesByRequestedTime"]=$severeCasesByRequestedTime;

  $hospitalBedsByRequestedTime=getHospitalBedsByRequestedTime($totalHospitalBeds,$severeCasesByRequestedTime);
  $impact["hospitalBedsByRequestedTime"]=$hospitalBedsByRequestedTime;

  $casesForICUByRequestedTime=getCasesForICUForICUByRequestedTime($infectionsByRequestedTime);
  $impact["casesForICUByRequestedTime"]=$casesForICUByRequestedTime;

  $casesForVentilatorsByRequestedTime=getCasesForVentilatorsByRequestedTime($infectionsByRequestedTime);
  $impact["casesForVentilatorsByRequestedTime"]=$casesForVentilatorsByRequestedTime;

  $dollarsInFlight=getDollarsInFlight($infectionsByRequestedTime,$duration,$periodType,$avgDailyIncomeInUSD,$avgDailyIncomePopulation);
  $impact["dollarsInFlight"]=$dollarsInFlight;

  return $impact;
}

function getSeverImpact($reportedCases,$duration,$periodType,$totalHospitalBeds,$avgDailyIncomeInUSD,$avgDailyIncomePopulation){
  $severeImpact=[];
  $currentlyInfected_severe=getCurrentlyInfectedForSeverImpact($reportedCases);
  $severeImpact["currentlyInfected"]=$currentlyInfected_severe;

  $infectionsByRequestedTime_severe=getInfectionsByRequestedTime($currentlyInfected_severe,$duration,$periodType);
  $severeImpact["infectionsByRequestedTime"]=$infectionsByRequestedTime_severe;

  $severeCasesByRequestedTime_severe=getSevereCasesByRequestedTime($infectionsByRequestedTime_severe);
  $severeImpact["severeCasesByRequestedTime"]=$severeCasesByRequestedTime_severe;

  $hospitalBedsByRequestedTime=getHospitalBedsByRequestedTime($totalHospitalBeds,$severeCasesByRequestedTime_severe);
  $severeImpact["hospitalBedsByRequestedTime"]=$hospitalBedsByRequestedTime;

  $casesForICUByRequestedTime=getCasesForICUForICUByRequestedTime($infectionsByRequestedTime_severe);
  $severeImpact["casesForICUByRequestedTime"]=$casesForICUByRequestedTime;

  $casesForVentilatorsByRequestedTime=getCasesForVentilatorsByRequestedTime($infectionsByRequestedTime_severe);
  $severeImpact["casesForVentilatorsByRequestedTime"]=$casesForVentilatorsByRequestedTime;

  $dollarsInFlight=getDollarsInFlight($infectionsByRequestedTime_severe,$duration,$periodType,$avgDailyIncomeInUSD,$avgDailyIncomePopulation);
  $severeImpact["dollarsInFlight"]=$dollarsInFlight;

  return $severeImpact;
}


function covid19ImpactEstimator($data)
{
  
  //Data Needed functions
  $reportedCases=$data["reportedCases"];
  $duration=$data["timeToElapse"];
  $periodType=$data["periodType"];
  $totalHospitalBeds=$data["totalHospitalBeds"];
  $avgDailyIncomeInUSD=$data["region"]["avgDailyIncomeInUSD"];
  $avgDailyIncomePopulation=$data["region"]["avgDailyIncomePopulation"];

 
  $output["data"] =$data;
  $output["impact"]=getImpact($reportedCases,$duration,$periodType,$totalHospitalBeds,$avgDailyIncomeInUSD,$avgDailyIncomePopulation);
  $output["severeImpact"]=getSeverImpact($reportedCases,$duration,$periodType,$totalHospitalBeds,$avgDailyIncomeInUSD,$avgDailyIncomePopulation);
  $data=$output;
  
  return $data;
}

