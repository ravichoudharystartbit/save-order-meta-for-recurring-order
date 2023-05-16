<?php
//You need to enable this code($_GET['check']) and comment whole code. Afterthat you can call this PHP server path on the onfleet. This code will call on 'Task Fail' event on onfleet.
//echo $_GET['check']; 
require_once './vendor/autoload.php';
use Onfleet\Onfleet;

$data = file_get_contents('php://input');
$webhookContentTaskFailed = json_decode($data);
if(!empty($webhookContentTaskFailed)){ 
   	 
	$onfleet = new Onfleet("onfleet-api");
    //CREATE TASK ON ONFLEET
    if($onfleet->verifyKey() == true){       
       //It will recreate the order on the onfleet                        
       $onfleet->tasks->clone($webhookContentTaskFailed->actionContext->id);
    }              
}
?>