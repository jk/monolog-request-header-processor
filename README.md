# Request Header Monolog Processor
I needed something to log and debug my Amazon Alexa skill which sends POST request to my server. So here it is.

## How to use it

```php
use Monolog\Logger;

$log = new Monolog\Logger('test');
$log->pushProcessor(new \JK\Monolog\Processor\RequestHeaderProcessor());
$log->pushHandler(…);

$log->notice('Noticed something');
```

## Sample output
This is the sample output of a normal GET request

```plain
[2017-08-28 13:04:09] test.NOTICE: Noticed something [] {"Host":"localhost","Connection":"keep-alive","Cache-Control":"max-age=0","Upgrade-Insecure-Requests":"1","User-Agent":"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.101 Safari/537.36","Accept":"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8","DNT":"1","Accept-Encoding":"gzip, deflate, br","Accept-Language":"de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4"}
```

So the extra information in pretty print is:

```json
{  
	"Host":"localhost",
	"Connection":"keep-alive",
	"Cache-Control":"max-age=0",
	"Upgrade-Insecure-Requests":"1",
	"User-Agent":"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.101 Safari/537.36",
	"Accept":"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8",
	"DNT":"1",
	"Accept-Encoding":"gzip, deflate, br",
	"Accept-Language":"de-DE,de;q=0.8,en-US;q=0.6,en;q=0.4"
}
```
