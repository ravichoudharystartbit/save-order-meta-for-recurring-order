<?php
//You need to enable this code($_GET['check']) and comment whole code. Afterthat you can call this PHP server path on the onfleet. This code will call on 'Task Fail' event on onfleet.
//echo $_GET['check']; 
require_once './vendor/autoload.php';
use Onfleet\Onfleet;

$datawebhook = file_get_contents('php://input');
$webhookTaskFailed = json_decode($datawebhook);
if(!empty($webhookTaskFailed)){ 
   $onfleetData = new Onfleet("onfleet-api");
    //CREATE TASK ON ONFLEET
    if($onfleetData->verifyKey() == true){       
       //It will recreate the order on the onfleet                        
       $onfleetData->tasks->clone($webhookTaskFailed->actionContext->id);
    }              
}
?>
