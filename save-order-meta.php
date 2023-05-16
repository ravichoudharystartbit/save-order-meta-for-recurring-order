<?php 
header("Access-Control-Allow-Origin: *");
//install ShopifySDK
require_once './vendor/autoload.php';

function verify_webhook($data, $hmac_header, $app_api_secret) {
    $calculated_hmac = base64_encode(hash_hmac('sha256', $data, $app_api_secret, true));
   return $calculated_hmac;    
}

// Set vars for Shopify webhook verification
$webhookContent = '';
$customerID = '';
$countryCode = '';
$hmac_header = $_SERVER['HTTP_X_SHOPIFY_HMAC_SHA256'];
$data = file_get_contents('php://input');
$verified = verify_webhook($data, $hmac_header, "shared-secret");
$webhookContents = json_decode($data);
			if(isset($verified) && !empty($webhookContents)){  
			  
			   $deliveryDate = $webhookContents->created_at;
			   if(isset($webhookContents->billing_address->country_code)){
			      $countryCode = $webhookContents->billing_address->country_code; 
			    }
			   if($webhookContents->tags != ''){
			    	//check recurring order by recharge recurring order
			        if (strpos($webhookContents->tags, 'Subscription Recurring Order') !== false ||  (strpos($webhookContents->tags, 'recurring_order') !== false)) {  
			        	//get recurring order customer id         
			            $customerID =  $webhookContents->customer->id;
			        }
			    }
			  
			}else{
			   $customerID = ''; 
			}
			/*get all customer order*/
			            $config = array(
			                'ShopUrl' => 'test.myshopify.com',
			                'ApiKey' => 'xxxxx',
			                'SharedSecret' => 'xxxxxxx',
			                'Password' => 'xxxxx'
			            );
			//Call ShopifySDK
			$shopifyData = new PHPShopify\ShopifySDK($config);

			if($customerID != '' && isset($customerID)){

			$params = array(
			    'status' => 'any'
			);
			//get customer detail           
			$customeOrderData = $shopifyData->Customer($customerID)->Order->get($params);
			$customerNoteAttrValue = '';
			$customerId = '';
            $customeNoteVal = '';
            
            //LOOP FOR FIND ORDER NOTE META VALUE
            foreach($customeOrderData as $customeOrderDatas){
            	//find 
                if((strpos($customeOrderDatas['tags'], 'Subscription Recurring Order') !== false) ||  (strpos($customeOrderDatas['tags'], 'recurring_order') !== false)) {
                    //assign order id and note value in variable             
                    $customeNoteVal = $customeOrderDatas['note'];
                    if($customeNoteVal != ''){
                        break;
                    } 

                }//if
            }//foreach
            
            //INCASE OF ORDER DON'T HAVE SUBSCRIPTION TAG THAN FINDING BY ACTIVE TAG TO GET ORDER NOTE VALUE
            if($customeNoteVal == ''){
                foreach($customeOrderData as $customeOrderDatas){
                    if (!empty($customeOrderDatas['customer']['tags'])) {      
                           if((strpos($customeOrderDatas['customer']['tags'], 'active_subscriber') !== false) ||  (strpos($customeOrderDatas['customer']['tags'], 'Active Subscriber') !== false)){     
                           
                            //assign order id and note value in variable 
                             $customeNoteVal = $customeOrderDatas['note'];
                            if($customeNoteVal != ''){
                                break;
                            } 
                        } 

                    } //empty
                } //foreach
              }
           

    $customerMetaKey = $deliveryDate;
     //order meta update      
    if( isset($customerMetaKey) && $customerMetaKey != ''){
         
          $dateformat = date("Y/m/d", strtotime($customerMetaKey));
          $updateDate = array (
           "note_attributes" => [
                  [
                      "name" => "mata-key",
                      "value" => $dateformat
                  ]
              ]
           
           );
          //Save/Update Recurring Order Meta Value
         $paramordesss = $shopifyData->Order($customeOrderData[0]['id'])->put($updateDate);
         
    }

?>
