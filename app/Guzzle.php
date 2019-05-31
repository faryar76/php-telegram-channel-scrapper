<?php 

namespace Faryar76;
use GuzzleHttp\Client;
class Guzzle 
{
    public $client;
    public $response;
    public function __construct()
    {
        $this->client=new Client();
    }
    public function get($url)
    {
        $this->response=$this->client->get($url);
        return $this;
    }
    public function getContents()
    {
        return $this->response->getBody();
    }
    public function getStatus()
    {
        return $this->response->getStatusCode();
    }
}