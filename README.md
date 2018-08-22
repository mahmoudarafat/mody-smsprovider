## mody-smsgateway - Laravel Multiple sms provider gateway package

# Package is still Under Construction

## Introduction

    /*
     * smsprovider package provides multiple connection with many sms providers.
     * just set your provider configuration and start sending messages.
     *
     * two officially plans:
     *      [user, group]
     *   user plan is one user provider, that works only for one user [authenticated user].
     *   group is multiple user provider, that services a company users maybe,
     *      that shares the same sms provider.
     *
     *  with group plan, your providers will have an account id number column.
     *  when calling your SMSProvider::configProvider() method,
     *      you will need this session to be set: session()->put('group_id', $group_id);
     *
     *
     * package will create five tables .
     *      [
     *          'sms_providers' => 'container of providers you have',
     *          'sms_providers_additional_params' => 'necessary parameters we need for sending sms',
     *          'sms_provider_messages' => 'messages you sent either success of failed with error codes',
     *          'sms_direct_messages' => 'template messages you created for quick sending'
     *          'sms_provider_track_activity' => 'track user activity while using package methods'
     *      ]
     */

----------------------------------------------------------------------

### Installation
      composer require mody/smsprovider:dev-master 


## after installation, do this:

#### 1. config/app.php
  a. service provider 
``` php
  mody\smsprovider\SMSGatewayServiceProvider::class,
```
  b. alias
  ```php
  'SMSProvider' => mody\smsprovider\Facades\SMSProvider::class,
```

#### 2. clear cache
	php artisan config:cache

#### 3. publish assets 
	php artisan vendor:publish
	
and you may see options, choose this one:
	
	Provider: mody\smsprovider\SMSGatewayServiceProvider

#### 4. config/smsgatewayConfig.php

choose your plan [individual user or group of users]
    
    'plan' => 'user'
    
choose if you want to track package activity
    
    'track' => true

#### 5. run this command to generate necessary tables

	php artisan smsprovider:tables

--------------------------------------------------------------------------

# Features:
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
 14. add/edit template messages.
 15. send template messages.
 ```

------------------------------------------------------------------------------
 
### Very important: 
`use this session with group plan`
	
```php
session()->put('group_id', $group_id);
```
 
 -----------------------------------------------------------------------------
 
# How to use SMSProvider:

return new provider setup view:
    Note: You can use it to return view in new url or use this given one.
	
```php
	SMSProvider::configProvider();
	
	route('smsprovider.providers.setup');
	
	url('smsprovider.setup');
```
	
send new sms to number or multiple numbers
```php
	SMSProvider::sendSMS($message, $numbers);
```
Example

		SMSProvider::sendSMS('hi, Mahmoud', '20106xxxxxxx');
	
		SMSProvider::sendSMS('hi, group member', '20106xxxxxxx,0120xxxxxxx,20111xxxxxxx');
		
*** expected response ***
	
	'0: error_code' => 'sending failed, and error code is given',
	       '1'      => 'messege delivered successfully',
	       '2'      => 'no response from destination or connection error'

return a single provider configuartion view/edit *view*
```php
	SMSProvider::updateProvider($provider_id);
```
	
move provider to trash [soft delete]  
```php
	SMSProvider::deleteProvider($provider_id');
```
*** expected response ***	

	true or false

destroy provider [becareful, deleting provider means that you will lose {configs, messages, ...}]
```php
	SMSProvider::destroyProvider($provider_id');
```

*** expected response ***	

	true or false

set default provider
```php
	SMSProvider::setDefaultProvider($provider_id);
```

*** expected response ***	

	true or false
	
recover deleted provider
```php
	SMSProvider::recoverProvider($provider_id);
```

*** expected response ***	

	true or false


remove default provider 
```php
	SMSProvider::removeDefaultProvider();
```

*** expected response ***	

	true or false
		
set default provider 
```php
	SMSProvider::removeDefaultProvider($provider_id);
```

*** expected response ***	

	true or false


### get providers => [20/page]
******return collection for auth user******
```php
	SMSProvider::myProviders();
```

******return collection for group****** 
```php
	SMSProvider::groupProviders();
```
******return view for auth user****** 
```php
	SMSProvider::myProvidersView();
	
	route('smsprovider.providers.user-providers');
	
	url('smsprovider/user-providers');
```
	
******return view for group******
```php
	SMSProvider::groupProvidersView();
		
	route('smsprovider.providers.group-providers');
	
	url('smsprovider/group-providers');
```
	

## get trashed providers 20/page 
******return collection for auth user****** 
```php
	SMSProvider::myTrashedProviders();
```
******return collection for group******
```php
	SMSProvider::groupTrashedProviders();
```
******return view for auth user******
```php
	SMSProvider::myTrashedProvidersView();
	
	route('smsprovider.providers.user-trashed-providers');
	
	url('smsprovider/user-trashed-providers');
```
	
******return view for group******
```php
	SMSProvider::groupTrashedProvidersView();
		
	route('smsprovider.providers.group-trashed-providers');
	
	url('smsprovider/group-trashed-providers');
```
		
-------------------------------------------------------------------------------
