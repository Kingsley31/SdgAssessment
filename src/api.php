<?php
    require_once('estimator.php');

    

    $start = microtime(true);
    $format="";
    if(isset($_GET["f"])){
        $format=$_GET["f"];
    }
    $data=json_decode(file_get_contents('php://input'), true);
    if($format=="json"){
        header('Content-Type: application/json');
        $output=json_encode(covid19ImpactEstimator($data));
        $time_elapsed_secs = (microtime(true) - $start)*1000;
        $path=$_SERVER['REQUEST_URI'];
        $get_http_response_code=http_response_code();
        $log=$_SERVER['REQUEST_METHOD']."   ".$path."    ".$get_http_response_code." ".intval($time_elapsed_secs)."ms \n";
        file_put_contents('log.txt', $log, FILE_APPEND);
        echo $output;
    }elseif ($format=="xml") {
        header('Content-Type: application/xml');
        $xml = new SimpleXMLElement('<root/>');
        $array_output=covid19ImpactEstimator($data);
        array_walk_recursive($array_output, array ($xml, 'addChild'));
        $time_elapsed_secs = (microtime(true) - $start)*1000;
        $path=$_SERVER['REQUEST_URI'];
        $get_http_response_code=http_response_code();
        $log=$_SERVER['REQUEST_METHOD']."   ".$path."    ".$get_http_response_code." ".intval($time_elapsed_secs)."ms \n";
        file_put_contents('log.txt', $log, FILE_APPEND);
        echo $xml->asXML();
    }elseif ($format=="logs") {
        header('Content-Type: text/plain');
        $logs=file_get_contents("log.txt");
        echo $logs;
    }else{
        header('Content-Type: application/json');
        $output=json_encode(covid19ImpactEstimator($data));
        $time_elapsed_secs = (microtime(true) - $start)*1000;
        $path=$_SERVER['REQUEST_URI'];
        $get_http_response_code=http_response_code();
        $log=$_SERVER['REQUEST_METHOD']."   ".$path."    ".$get_http_response_code." ".intval($time_elapsed_secs)."ms \n";
        file_put_contents('log.txt', $log, FILE_APPEND);
        echo $output;
    }
        
    
   
    


   

?>