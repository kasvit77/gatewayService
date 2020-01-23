<?php

require 'GatewayService/PostsServiceInterface.php';
require 'GatewayService/rest/PostsClient.php';
class PostsService implements PostsServiceInterface
{
    /**
     * @var PostsClient
     */
    private $client;



    public  function getClient($id){

        $this->client=(new PostsClient())->pull($id);
       if($this->client)
       {

           return json_decode($this->client,true);
       }
       else{
           return null;
       }

    }

    public function pushPosts($param){


    return $this->getClient($param);

    }

}