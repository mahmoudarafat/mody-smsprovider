# mody-smsgateway


#Laravel Multiple sms provider gateway package

#Under Construction

### Installation
     ` composer require mody/smsprovider:dev-master `


### after installation, do this:

## config/app.php
  #1. service provider 
``` php
  mody\smsprovider\SMSGatewayServiceProvider::class,
```
  ##2. alias
  ```php
  'SMSProvider' => mody\smsprovider\Facades\SMSProvider::class,
```
## 	config/smsgatewayConfig.php
	  --> choose your plan [individual user or group of users]
    ```php
    'plan' => 'user'
    ```
    --> choose if you want to track package activity
    
    ```php
    'track' => true
    ```

-----------------------------------------------------------------------------

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
