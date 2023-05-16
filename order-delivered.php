<?php 
//You need to enable this code($_GET['check']) and comment whole code. Afterthat you can call this PHP server path on the onfleet.  This code will call on 'Order Delivered' event on onfleet.
//echo $_GET['check']; 

require __DIR__ . '/vendor/autoload.php';
use Twilio\Rest\Client;

function remove_sp_chr($phone)
{
    $result = str_replace(array("#", "-", "(", ")", " "), '', $phone);
    return $result;
}
$OnfleetData = file_get_contents('php://input');
$OnfleetData = json_decode($OnfleetData);

//define empty variable   
$customerPhone = '';
$orderNumber = '';
    if($OnfleetData->data->task->metadata[0]->name == 'shopify_order_id' && $OnfleetData->data->task->metadata[0]->value != '' ){
            //get Onfleet current order id
            $onfleetShopifyOrderId =  $OnfleetData->data->task->metadata[0]->value;
            //get Onfleet current order customer number
            $customePhone = 'xxxx-xxx-xxx';
            //get Onfleet current order number
            $orderNumber = '1325688';
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
    //Send SMS order successfully delivered.  
    if($fromNumeber != ''){
             //twilio SMS API
            // Your Account SID and Auth Token from twilio.com/console
            $account_sid = 'twilio-sid';
            $auth_token = 'twilio-token';
            $senderMessage = 'twilio-message-id';
            $client = new Client($account_sid, $auth_token);
            
            $orderConfimation = "Your order #".$orderNumber." has been successfully delivered.";
            try{
                  echo $client->messages->create(
                        // Where to send a text message (your cell phone?)
                        $fromNumeber,
                        array(
                            "messagingServiceSid" => $senderMessage,
                            'body' => $orderConfimation
                        )
                    );
              }catch(Exception $e){
                   echo $e->getCode() . ' : ' . $e->getMessage()."<br>";
              }
            }
    //End Twilio SMS APi

?>
