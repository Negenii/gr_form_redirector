# gr form redirector

GR form redirector is a tool to add subscribers to GetResponse via html-form and also to redirect subscriber to specific URL if his email is already subscribed to the campaign, which is missing in GetResponse plain html forms.

## Install

Put subscribe.php and GetResponseAPI3.class.php to some folder on your site.

## Config

In subscribe.php edit following lines:

```php
$getresponse = new GetResponse('YOUR-API-KEY-HERE');
$domain = $getresponse->enterprise_domain = 'YOUR GR360/ENTERPRISE DOMAIN HERE';
```
Ask your account manager for API URL, or try one of below. Uncomment one of these lines
```php
$getresponse->api_url = 'https://api3.getresponse360.com/v3'; 
$getresponse->api_url = 'https://api3.getresponse360.pl/v3'; 
```

## Usage

Copy html form in your GetResponse Enterprise account. You will get hmtl form code like this.
![html_form](https://user-images.githubusercontent.com/13003022/28489793-621fc3d4-6ed4-11e7-88e7-2ea16986363a.png)

1. Copy it to your website.
2. Point form action to your subscribe.php.
If it placed in your root folder, your line will look like this:
```html
<form action="subscribe.php" accept-charset="utf-8" method="post">
```
3. Add URL to redirect user when his email already exists in campaign.```html
<input type="hidden"type="text" name="alreadyredirect" value="http://YOUR-URL.HERE"
```
If this url is not set and subscriber exists in campaign, visitor will be redirected back. 


## Known issues

You need to contact your GetResponse Enterprise (360) account manager to whitelist your sites IP address. Otherwise GetResponse will likely block adding subscribers after a while.

Subscribers IP address is replaced with your site IP address.

