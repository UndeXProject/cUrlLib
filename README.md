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

### Example get data and enable cache
```PHP
<?php
include_once __DIR__.'/cURL.class.php';

$cookie_file = __DIR__.'/cookies.txt';
$curl = new cUrlLib($cookie_file);

$curl->CACHE = true;
$curl->CACHE_DIR = __DIR__.'/cache';
$data = $curl->get('http://example.com/');
print($data);
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
| `getCacheData`      |   link `str` - url adress                       | `Mixed` - data or  | Get cache from url                                     |
|                     |                                                 | FALSE              |                                                        |

And support next variables set:
| Variable            | Type    | Description                                                |
| ------------------- | ------- | ---------------------------------------------------------- |
| `URL`               | String  | URL adress for request                                     |
| `UA_LIST`           | Array   | List User agents                                           |
| `REF_LIST`          | Array   | List referer                                               |
| `CACHE`             | Bool    | Enable or disable cache engine                             |
| `CACHE_DIR`         | String  | Path to cache directory. !!! Without end backslash !!!     |
| `CACHE_FILER`       | Array   | List filter from mime type                                 |
| `CACHE_MAX_SIZE`    | Int     | Max cache filesize from bytes. Zero - unlimeted            |
