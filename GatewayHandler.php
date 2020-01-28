<?php

require 'GatewayService/RedisService.php';
class GatewayHandler
{
    /**
     * @var PostsServiceInterface
     */
    private $service;

    /**
     * @var RedisService
     */
    private $redis;

    /**
     *
     * @param PostsServiceInterface $postsService
     */
    public function __construct(PostsServiceInterface $postsService)
    {
        $this->service = $postsService;
        $this->redis=new RedisService();
    }

    public function handle($id){

       $result=$this->service->pushPosts($id);
        $result=$this->redis->Save($id);
       return $result;

    }



}