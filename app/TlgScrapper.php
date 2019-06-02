<?php

namespace Faryar76;
use Faryar76\Message;
use GuzzleHttp\Client;
class TlgScrapper
{
    protected $rawContent;
    protected $client;
    protected $patterns=[
        'name'=>'/<div class="tgme_header_title">(.*?)<\/div>/is',
        'count_memmber'=>'/<span class="counter_value">(.*?)<\/span> <span class="counter_type">members<\/span>/is',
        'count_link'=>'/videos.*?<span class="counter_value">(.*?)<\/span> <span class="counter_type">links<\/span>/is',
        'count_video'=>'/photos.*?<span class="counter_value">(.*?)<\/span> <span class="counter_type">videos<\/span>/is',
        'count_photo'=>'/members.*?<span class="counter_value">(.*?)<\/span> <span class="counter_type">photos<\/span>/is',
        'description'=>'/  <meta name="twitter:description" content="(.*?)">/is',
        'bubbles'=>'/(<div class="tgme_widget_message_wrap js-widget_message_wrap.*?">.*?datetime.*?<\/div>)/ms',
        'image'=>'/<meta property="og:image" content="(.*?)">/is'
    ];
    public function __construct($httpClient=null)
    {
        if(!$httpClient)
        {
            $httpClient=new Client();
        }
        $this->client=$httpClient;
        $this->normalizer=new Normalizer();
    }
    public function load($username)
    {
        $username=$this->normalizer->username($username);
        $url=sprintf('https://t.me/s/%s',$username);
        $this->rawContent=$this->client->get($url)->getBody()->getContents();
        return $this;
    }
    public function getName()
    {
        return $this->preg('name')[1];
    }
    public function getMemmberCount()
    {
        return $this->preg('count_memmber')[1];
    }
    public function getVideoCount()
    {
        return $this->preg('count_video')[1];
    }
    public function getDescription()
    {
        return str_replace("\n",'',$this->preg('description')[1]);
    }
    public function getLinkCount()
    {
        return $this->preg('count_link')[1];
    }
    public function getPhotoCount()
    {
        return $this->preg('count_photo')[1];
    }
    public function getImage()
    {
        return $this->preg('image')[1];
    }
    public function getMessages()
    {
        preg_match_all($this->patterns['bubbles'],$this->rawContent,$matches);
        foreach ($matches[1] as $key => $value) {
            $result[]=$this->parseMessage($value);
        }
        return new Collection($result);
    }
    public function preg($key)
    {
        preg_match($this->patterns[$key],$this->rawContent,$matches);
        return $matches; 
    }
    public function parseMessage($message)
    {
        $result=[];
        // id
        $id='/class="tgme_widget_message_date".*?href="https:\/\/t\.me\/telegram\/([0-9]+)/ism';
        preg_match($id,$message,$res);
        $result['id']=(int) $res[1];
        // get date
        $date='/<time datetime="(.*?)">[0-9:]+<\/time>/is';
        preg_match($date,$message,$res);
        $result['date']['date']=$res[1];
        $result['date']['unix']=strtotime($res[1]);
        // get viws
        $view='/class="tgme_widget_message_views">(.*?)<\/span>/ms';
        preg_match($view,$message,$res);
        $result['views']=$res[1] ?? "";
        // text
        $text='/class="tgme_widget_message_text js-message_text".*?>(.*?)<\/div>/is';
        preg_match($text,$message,$res);
        $result['text']=strip_tags(trim(preg_replace('/\s\s+/', ' ', html_entity_decode($res[1]??"",ENT_QUOTES))));
        return new Message($result);
    }
}
