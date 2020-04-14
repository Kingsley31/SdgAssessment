<?php
    require_once('estimator.php');
    
    $format="";
    if(isset($_GET["f"])){
        $format=$_GET["f"];
    }
    $data=json_decode(file_get_contents('php://input'), true);
    if($format=="json"){
        header('Content-Type: application/json');
        $output=json_encode(covid19ImpactEstimator($data));
        echo $output;
    }elseif ($format=="xml") {
        header('Content-Type: application/xml');
        $xml = new SimpleXMLElement('<root/>');
        $array_output=covid19ImpactEstimator($data);
        array_walk_recursive($array_output, array ($xml, 'addChild'));
        echo $xml->asXML();
    }else{
        header('Content-Type: application/json');
        $output=json_encode(covid19ImpactEstimator($data));
        echo $output;
    }
        
    
   
    


   

?>