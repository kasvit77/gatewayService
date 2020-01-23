<?php


interface PostsServiceInterface
{

    /**
     * @param $postsId
     * @throws \Exception
     */
    public function pushPosts($postsId);

}