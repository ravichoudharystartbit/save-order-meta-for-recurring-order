#This code will help to create an order meta of recurring order(Recharge Recurring Order). You can use Shopify SDK to update the order meta of recurring orders. You can also send the order detail to the customer by SMS(Twilio APIs). I am also using Onfleet API to send current order status to customers by SMS.

a) You need to install PHP SHopifySDK on your PHP server.

	composer require phpclassic/php-shopify

b) You need to install PHP Omnisend on your PHP server.
	
	composer require onfleet/php-onfleet

c) You need to use twilio APIs to send SMS to customer
	$account_sid = 'twilio-sid';
    $auth_token = 'twilio-token';
    $senderMessageId = 'twilio-message-id';

d) You can save the order meta of recurring orders (recharge order). Please follow the liquid code from 'save-order-meta.php'

***Save Order Meta For Recurring Order:-***
<p align="center">
  <img src="/delivery-date.png" width="350">
</p>

e) This code will help to send the Onfleet current order status to the customer using Twilio API. Please follow the liquid code from 'driver-pickup-order.php'.

***Onfleet Event:-***
<p align="center">
  <img src="/onfleet-webhook.png" width="350">
</p>
***Onfleet Event LIst:-***
<p align="center">
  <img src="/event-list-onfleet.png" width="350">
</p>

f) This code will help to send SMS to customers using Twilio API.

$client->messages->create(
                        // Where to send a text message (your cell phone?)
                        $fromNumeber,
                        array(
                            "messagingServiceSid" => $senderMessageId,
                            'body' => $orderConfimationText
                        )
                    );

g) If an order will not reach due to any reason then this code will help to recreate the order in the onfleet. Please follow the liquid code from 'order-recreate-onfleet.php'.    

h) Instruction to use PHP files in the Onfleet.

Please comment whole code in the PHP file. Please enable this code 'echo $_GET['check']'. After that, you can call this file in the onfleet.   