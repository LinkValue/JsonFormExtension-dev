formExtension
=============

This extension allows you to do the following :

```php
// Request with CONTENT_TYPE : json and a json body such as '{"hello": "world", "foo": "bar"}'
$form->handleRequest($request);

$form->submit('{"hello": "world", "foo": "bar"}');
```

Installation
------
**You need to add your bundle containing the extension after the FrameworkBundle.**
