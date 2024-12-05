<?php
require_once 'nusoap.php';
require_once 'database-service.php';

$wsdl = "http://localhost/soap/soap-service.php?wsdl";

$server = new soap_server();
$server->configureWSDL("soap service","http://localhost/soap/soap-service.php");
$server->register(
    'insert_quest', 
    array(  
        'enonce' => 'xsd:string', 
        'reponses' => 'xsd:string',  
        'answer' => 'xsd:string'  
    ),
    array(  
        'result' => 'xsd:string' 
    ),
    "http://localhost/soap/soap-service.php",
    false,
    'rpc', 
    'encoded', 
    'Insert a new question'
);


$server->register(
    'get_quest',  
    array( 
        'enonce' => 'xsd:string'  
    ),
    array(  
        'result' => 'xsd:string'  
    ),
    "http://localhost/soap/soap-service.php",
    false,
    'rpc', 
    'encoded', 
    'Get a specific question',    
);

$server->register(
    'delete_quest', 
    array(  
        'enonce' => 'xsd:string',  
    ),
    array(  
        'result' => 'xsd:string'  
    ),
    "http://localhost/soap/soap-service.php",
    false,
    'rpc', 
    'encoded', 
    'Delete question'
);

$server->register(
    'get_quests',  
    array(),  
    array(  
        'result' => 'xsd:Array'  
    ),
    "http://localhost/soap/soap-service.php",
    false,
    'rpc', 
    'encoded', 
    'Get all questions',  
);
function err_handler(){
    throw new SoapFault('500',"Something went wrong! 😒");
}
function get_quests() {
    try{
        return get_questions();
    }catch(SoapFault $e){
        return $e->getMessage();
    }
}
function get_quest($enonce) {
    try{
        $res = get_question($enonce);
        if(json_decode($res)!=null) return $res;
        return new soap_fault('404','no question with that enonce exists');
    }catch(SoapFault $e){
        err_handler();
    }
    
}
function insert_quest($enonce, $reponses, $answer) {
    
    try{
        $quest = new Question($enonce, $reponses, $answer);
        if(json_decode(get_question($enonce))!=null) 
            return new soap_fault('409',"question already exists");
        
        if (!in_array($answer,explode(',', $reponses))){
            return new soap_fault('406','make sure the right answer exists in choices!');
        }

        $success = post_question($quest);
        if ($success) { return "success";}else{ return "failed!";}
    }catch(SoapFault $e){
        err_handler();
    }

}
function delete_quest($enonce) {
    try{
        if(json_decode(get_question($enonce))==null) 
            return new soap_fault('404',"question doesn't exist");
        $success = delete_question($enonce);
        if ($success) { return "success";}else{ return "failed!";}
    }catch(SoapFault $e){
        err_handler();
    }

}

$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA :"";
$server->service(file_get_contents("php://input"))
?>