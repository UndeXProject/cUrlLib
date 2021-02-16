# cURL Library
### cURL Library for PHP
Tested on PHP version: `7.4`

### Example for using
```PHP
<?php
include_once __DIR__.'/cURL.class.php';

$cookie_file = __DIR__.'/cookies.txt';
$curl = new cUrlLib($cookie_file);

print($curl->get('http://example.com/'));
?>
```

### Example set custom user agent
```PHP
<?php
include_once __DIR__.'/cURL.class.php';

$cookie_file = __DIR__.'/cookies.txt';
$curl = new cUrlLib($cookie_file);
// Set custom useragent (masking in Googlebot)
$curl->setOption(CURLOPT_USERAGENT, 'Googlebot/2.1 (+http://www.google.com/bot.html)');
// Get data
print($curl->get('http://example.com/'));
?>
```

### Example get headers and data
```PHP
<?php
header("Content-type: text/plain"); // set header for correct drawing page
include_once __DIR__.'/cURL.class.php';

$cookie_file = __DIR__.'/cookies.txt';
$curl = new cUrlLib($cookie_file);
// Set option for show headers
$curl->setOption(CURLOPT_HEADER, true);
// Get data with disable gzip
print($curl->get('http://example.com/', false));
?>
```

# Actions information
cURL library support next actions:
| Action              | Params                                          | Return             | Description                                            |
| ------------------- | ----------------------------------------------- | ------------------ | ------------------------------------------------------ |
| `construct`         |   cookie `str` - path to cookie file (required) | -                  | Construct function. Called in create object.           |
|                     |   url `str` - url adress                        |                    |                                                        |
| `setDefaultOptions` | -                                               | `Bool` - status    | Set dafault cURL options.                              |
| `setOption`         |   option `constant` - cURL option               | `Bool` - status    | Set custom cURL option, see                            |
|                     |   value `mixed` - cURL option value             |                    | https://www.php.net/manual/ru/function.curl-setopt.php |
| `open`              | -                                               | `CurlHandle` -     | Init cURL session.                                     |
|                     |                                                 | Hande (int)        |                                                        |
| `close`             | -                                               | `Bool` - status    | Close cURL session.                                    |
| `get`               |   url `str` - url adress                        | `String` - request | Get data from url.                                     |
|                     |   gzip `bool` - enable/disable gzip decoder     | data               |                                                        |
