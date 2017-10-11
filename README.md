# What it is
This is a non-official API client for Mobily.ws SMS service. We created it after struggling with their API. 
This is another attempt seconding one we already created. This one uses what they call the JSON API at their end!

# Usage
Simply create a client:

```php
	$client = new MobilyAPI\Client(username, password, senderName);
```

Then use the request you want:

```php
	$newMessage = new MobilyAPI\requests\SendMessage($client, numbers, message);
	$response = $newMessage->send(); // Send the request
	
	$newMessage->sent(); // Tells you if it was sent successfully
```

It's very simple and straight forward. Good luck.