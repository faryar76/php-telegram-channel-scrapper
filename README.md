# php-telegram-channel-scrapper
a pure php telegram channel scrapper library  without login!!

## installation 
```bash
composer require faryar76/php-telegram-scrapper
```

## usage
##### create instance from main class

```php
require __DIR__."/vendor/autoload.php";
use Faryar76\TlgScrapper;

$tlg=new TlgScrapper();
$tlg->load('a-telegram-channel-username');
```
## channel information
```php
$tlg->getName();          // channel name 
$tlg->getDescription();   // channel description  
$tlg->getimage();         // channel image  
$tlg->getMemmbercount();  // channel Memmbers count
$tlg->getLinkcount();     // channel links count
$tlg->getVideocount();    // channel videos count
$tlg->getPhotocount();    // channel photos count
```
#### for get last `20` posts
```php
$tlg->getMessages();
```
#### for get a posts with id
```php
$tlg->getMessages(25);
```
### for get a post data
```php
$tlg->getMessages()->first()->date;   //posts created at

$tlg->getMessages()->first()->views;  //posts view count

$tlg->getMessages()->first()->text;   //posts content as text

```




