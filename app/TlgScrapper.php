<?php

namespace Faryar76;

class TlgScrapper
{
    protected $rawContent;
    protected $client;
    protected $patterns=[
        'name'=>'/<div class="tgme_header_title">(.*?)<\/div>/is'
    ];
    public function __construct()
    {
        $this->client=new Guzzle();
        $this->normalizer=new Normalizer();
    }
    public function load($username)
    {
        $username=$this->normalizer->username($username);
        $url=sprintf('https://t.me/s/%s',$username);
        $this->rawContent=$this->client->get($url);
    }
    public function getName()
    {
        return $this->preg('name')[1];
    }
    public function preg($key)
    {
        preg_match($this->patterns['name'],$this->rawContent->getContents(),$matches);
        return $matches; 
    }
}
