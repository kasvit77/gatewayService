<?php
require 'vendor/predis/autoload.php';
require 'GatewayService/redis/RedisClient.php';
use Predis\Autoloader;

require 'vendor/autoload.php';
Autoloader::register();
class RedisService extends \RedisClient\RedisClient
{

    public function Save($posts){

       $this->Client()->set('foo', 'vitalik333');
     return  $this->Client()->get('foo');

    }

}