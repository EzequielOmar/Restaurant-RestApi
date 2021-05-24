<?php
class Middleware{
    protected $router;

    public function __construct($router){
        $this->router = $router;
    }

    public function __get($property){
        if (isset($this->router->{$property}))
            return $this->router->{$property};
        return null;
    }
}
?>