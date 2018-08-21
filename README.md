# mody-smsgateway


#Laravel Multiple sms provider gateway package

#Under Construction

### Installation
      composer require mody/smsprovider:dev-master 


## after installation, do this:

#### config/app.php
  #####1. service provider 
``` php
  mody\smsprovider\SMSGatewayServiceProvider::class,
```
  #####2. alias
  ```php
  'SMSProvider' => mody\smsprovider\Facades\SMSProvider::class,
```
## config/smsgatewayConfig.php

choose your plan [individual user or group of users]
    ```php
    'plan' => 'user'
    ```
choose if you want to track package activity
    
    ```php
    'track' => true
    ```
    
#Features:
 ```
 1. you can add one or more sms provider/gateway to your account/group.
 2. choose your default sms provider/gateway to send through it.
 3. simple view for your account/group sms providers/gateways.
 4. simple view for your account/group trash.
 5. configuration changing over the recorded providers/gateways.
 6. can move items to trash or destroy it for good.
 7. can recover trashed items.
 8. simple view for adding/updating any provider data.
 9. send sms to single number or group of numbers.
 10. simple view for messages sent/failed for account/group/provider. 
 11. call collection of providers/gateways.
 12. call collection of trashed providers/gateways.
 13. simple view for track activity.
 14. add template messages.
 15. send template messages.
 ```
# How to use SMSProvider:

return new provider setup view:
	SMSProvider::configProvider();

-----------------------------------------------------------------------------

send new sms to number or multiple numbers "xxxxx,zzzzzz,yyyyyy"
	SMSProvider::sendSMS($message, $numbers);
	Example:
		SMSProvider::sendSMS('hi, Mahmoud', '201065825376');
		SMSProvider::sendSMS('hi, group member', '201065825376,01004456235');

------------------------------------------------------------------------------

return only your providers view [even if you are in group]
	get plans you've configured
		SMSProvider::myProviders();

------------------------------------------------------------------------------

return group plan view
	SMSProvider::groupProviders();

------------------------------------------------------------------------------

return a single provider configuartion view/edit view
	SMSProvider::updateProvider($provider_id);

------------------------------------------------------------------------------

remove provider from usage list [soft delete]  
	SMSProvider::deleteProvider($provider_id');

------------------------------------------------------------------------------

destroy provider [becareful, deleting provider means that you will lose {configs, messages, ...}]
	SMSProvider::destroyProvider($provider_id');

------------------------------------------------------------------------------

set default provider
	SMSProvider::setDefaultProvider($provider_id);

------------------------------------------------------------------------------

recover deleted provider
	SMSProvider::recoverProvider($provider_id);

------------------------------------------------------------------------------

get trashed providers 
	return collection for auth user => 
		SMSProvider::myTrashedProviders();
	return collection for group => 
		SMSProvider::groupTrashedProviders();
	return view for auth user => 
		SMSProvider::myTrashedProvidersView();  
	return view for group => 
		SMSProvider::groupTrashedProvidersView();

-------------------------------------------------------------------------------

remove default provider 
	SMSProvider::removeDefaultProvider();

------------------------------------------------------------
-------------------
