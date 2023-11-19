<?php

namespace src\services;

class PostService
{
    private static $instance = null;
    private $post;

    private function __construct()
    {
        $this->post = $_POST;
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function get($postName = null)
    {
        // print_r($this->post);
        if ($postName) {
            return isset($this->post[$postName]) ? ($this->post[$postName] != null ?$this->post[$postName]:'') : false;
        } else {
            return $this->post;
        }
    }
}
