<?php
if (!class_exists('CurlClient')) {
    require_once($_SERVER['DOCUMENT_ROOT'] . '/api/GatewayService/rest/CurlClient.php');
}
class PostsClient extends CurlClient
{

    public function pull($id){

        $this->configure('https://jsonplaceholder.typicode.com/posts',$id?['id'=>$id]:'');

      return  $this->execute();
    }
}