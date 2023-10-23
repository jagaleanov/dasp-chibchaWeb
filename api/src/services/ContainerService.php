<?php

namespace src\services;

class ContainerService
{
    private static $instance = null;
    private $services = [];
    private $definitions = [];

    private function __construct() {}

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function register($name, $definition)
    {
        $this->definitions[$name] = $definition;
    }

    public function get($name)
    {
        if (!isset($this->services[$name])) {
            if (isset($this->definitions[$name])) {
                if ($this->definitions[$name] instanceof \Closure) {
                    $this->services[$name] = $this->definitions[$name]($this);
                } else {
                    $this->services[$name] = new $this->definitions[$name]();
                }
            } else {
                throw new \Exception("Service {$name} not found.");
            }
        }
        return $this->services[$name];
    }
}
