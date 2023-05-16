<?php 
//You need to enable this code($_GET['check']) and comment whole code. Afterthat you can call this PHP server path on the onfleet. This code will call on 'Task Start' event on onfleet.
//echo $_GET['check']; 
require __DIR__ . '/vendor/autoload.php';
use Twilio\Rest\Client;

//remove space and other string form the phone numeber
function remove_sp_chr($phone)
{
    $result = str_replace(array("#", "-", "(", ")", " "), '', $phone);
    return $result;
}

		//define empty variable
		$customePhone = '';
		$trackingUrl = '';
		$referenceNumber = '';
		//get onlfleet current order detail
		$webhookdata = file_get_contents('php://input');
       		$webhookAssignDriver = json_decode($webhookdata);	

        	if($webhookAssignDriver->data->task->metadata[0]->name == 'shopify_order_id' && $webhookAssignDriver->data->task->metadata[0]->value != '' ){
        		$onfleetShopifyOrderId =  $webhookAssignDriver->data->task->metadata[0]->value;
			$customePhone = 'xxxx-xxx-xxx';
			    if( $customePhone != ''){
				      $removeSpecialCharPhoneNum = remove_sp_chr($customePhone);
				      //check US condition according to your current order
				      if($selectShopifyOrderRow['order_location'] == "United States" && $selectShopifyOrderRow['order_location'] != ''){
					echo $fromNumeber = "+1".ltrim($removeSpecialCharPhoneNum, "0");
				      }else{
					echo $fromNumeber = ltrim($removeSpecialCharPhoneNum, "0");
				      }
			    }
		}	
		
		if($webhookAssignDriver->data->task->trackingURL != ''){	
			$trackUrl = $webhookAssignDriver->data->task->trackingURL;
		}
		if($webhookAssignDriver->data->task->shortId != ''){
			$reference = $webhookAssignDriver->data->task->shortId;
		}	
				
		
		if( $trackingUrl != '' && $customePhone != ''){
				//twilio SMS API
				// Your Account SID and Auth Token from twilio.com/console
				$account_sid = 'twilio-sid';
				$auth_token = 'twilio-token';
				$senderMessageIds = 'twilio-message-id';
		                
				$client = new Client($account_sid, $auth_token);

				$orderConfimation = "order will reach soon ".$reference." and you can track it here ".$trackUrl;
						try{
						echo $client->messages->create(
		             		 // Where to send a text message (your cell phone?)
				              $fromNumeber,
				              array(
				                  "messagingServiceSid" => $senderMessageIds,
				                  'body' => $orderConfimation
				              )
				          );
				        }catch(Exception $e){
		         			 echo $e->getCode() . ' : ' . $e->getMessage()."<br>";
		      			}
		            //End Twilio SMS APi
				}

?>
