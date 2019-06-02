<?php

namespace Faryar76;
use Faryar76\Message;
use GuzzleHttp\Client;
class TlgScrapper
{
    protected $rawContent;
    protected $client;
    protected $retryOnEagerLoad=1;
    protected $username;
    protected $messages=[];
    protected $channel_info_patterns=[
        'name'=>'/<div class="tgme_header_title">(.*?)<\/div>/is',
        'memmbercount'=>'/<span class="counter_value">(.*?)<\/span> <span class="counter_type">members<\/span>/is',
        'linkcount'=>'/videos.*?<span class="counter_value">(.*?)<\/span> <span class="counter_type">links<\/span>/is',
        'videocount'=>'/photos.*?<span class="counter_value">(.*?)<\/span> <span class="counter_type">videos<\/span>/is',
        'photocount'=>'/members.*?<span class="counter_value">(.*?)<\/span> <span class="counter_type">photos<\/span>/is',
        'description'=>'/  <meta name="twitter:description" content="(.*?)"/is',
        'bubbles'=>'/(<div class="tgme_widget_message_wrap js-widget_message_wrap.*?">.*?datetime.*?<\/div>)/ms',
        'image'=>'/<meta property="og:image" content="(.*?)">/is',
    ];
    protected $messaeg_patterns=[
        'id'=>'/class="tgme_widget_message_date".*?href="https:\/\/t\.me\/.*?\/([0-9]+)/ism',
        'date'=>'/<time datetime="(.*?)">[0-9:]+<\/time>/is',
        'views'=>'/class="tgme_widget_message_views">(.*?)<\/span>/ms',
        'text'=>'/class="tgme_widget_message_text js-message_text".*?>(.*?)<\/div>/is',
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
        $this->username=$username;
        $url=sprintf('https://t.me/s/%s',$username);
        $this->rawContent=$this->client->get($url)->getBody()->getContents();
        $this->prepareChannelContent();
        return $this;
    }
    protected function prepareChannelContent()
    {
        foreach ($this->channel_info_patterns as $key => $value) {
            preg_match($this->channel_info_patterns[$key],$this->rawContent,$matches);
            $this->info[$key]=$matches[1] ?? '';
        }
    }
    public function __call($method,$params)
    {
        if(method_exists($this,$method))
        {
            return $this->$method(...$params);
        }
        $key=strtolower(substr($method,3));
        if(array_key_exists($key,$this->info))
        {
            return $this->info[$key];
        }
    }
    public function getInfo()
    {
        return $this->info;
    }
    public function getDescription()
    {
        return str_replace("\n",'',$this->info['description']);
    }
    protected function spliteMessages()
    {
        preg_match_all($this->channel_info_patterns['bubbles'],$this->rawContent,$matches);
        foreach ($matches[1] as $value) {
            $this->messages[$this->parseMessage($value)->id]=$this->parseMessage($value);
        }
        return $this->messages;
    }
    public function eagerLoad($before)
    {
        $before=$before==null ? null : '?before='.$before ;
        $url=sprintf('https://t.me/s/%s'.$before,$this->username);
        $this->rawContent=$this->client->get($url)->getBody()->getContents();
        return $this;
    }
    public function getMessages($id=null,$try=0)
    {

        $result=$this->spliteMessages();
        if(!$id)
        {
            return new Collection(array_values($result));
        }
        if(array_key_exists($id,$result))
        {
            return $result[$id];
        }
        if($try >= $this->retryOnEagerLoad)
        {
            return [];
        }
        $this->eagerLoad($id+1);
        return $this->getMessages($id,$try+1);
    }    
    public function parseMessage($message)
    {
        $result=[];
        // id
        $patterns=$this->messaeg_patterns;
        preg_match($patterns['id'],$message,$res);
        $result['id']=(int) $res[1];
        // get date
        preg_match($patterns['date'],$message,$res);
        $result['date']['date']=$res[1];
        $result['date']['unix']=strtotime($res[1]);
        // get viws
        preg_match($patterns['views'],$message,$res);
        $result['views']=$res[1] ?? "";
        // text
        preg_match($patterns['text'],$message,$res);
        $result['text']=strip_tags(trim(preg_replace('/\s\s+/', ' ', html_entity_decode($res[1]??"",ENT_QUOTES))));
        return new Message($result);
    }
}
