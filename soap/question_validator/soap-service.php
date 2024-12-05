<?php
require_once 'nusoap.php';
require_once 'database-service.php';

$wsdl = "http://localhost/soap/soap-service.php?wsdl";

$server = new soap_server();
$server->configureWSDL("soap service","http://codetn.tn");
$server->register(
    'validate_quest', 
    array(  
        'enonce' => 'xsd:string',  
        'answer' => 'xsd:string'  
    ),
    array(  
        'result' => 'xsd:bool'  
    ),
    "http://codetn.tn",
    false,
    'rpc', 
    'encoded', 
    'validate a new question'
);
function err_handler(){
    return new soap_fault('500',null,"Something went wrong! 😒");
}

function validate_quest($enonce, $answer) {
    try{
        $res = get_question($enonce);
        $quest = json_decode($res);
        if($quest!=null) return json_encode(array('success'=>$quest->Answer == $answer));
        return new soap_fault('404','no question with that enonce exists');
    }catch(Unexpected){
        err_handler();
    }
}

$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA :"";
$server->service(file_get_contents("php://input"))
?>