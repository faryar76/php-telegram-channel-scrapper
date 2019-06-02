# php-telegram-channel-scrapper
a pure php telegram channel scrapper library  without login!!

## installation 
```
composer require faryar76/php-telegram-scrapper
```

## usage
##### create instance from main class

```
$tlg=new TlgScrapper();
$tlg->load('a-telegram-channel-username');
```
## channel information
```
$tlg->getName();          \\ channel name 
$tlg->getDescription();   \\ channel description  
$tlg->getimage();         \\ channel image  
$tlg->getMemmbercount();  \\ channel Memmbers count
$tlg->getLinkcount();     \\ channel links count
$tlg->getVideocount();    \\ channel videos count
$tlg->getPhotocount();    \\ channel photos count
```
#### for get last `20` posts
```
$tlg->getMessages();
```
#### for get a posts with id
```
$tlg->getMessages(25);
```
### for get a post data
```
$tlg->getMessages()->first()->date;   \\ posts created at

$tlg->getMessages()->first()->views;  \\posts view count

$tlg->getMessages()->first()->text;   \\posts content as text

```




