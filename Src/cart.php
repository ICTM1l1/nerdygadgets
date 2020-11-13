<?php
if(session_status() == PHP_SESSION_NONE) {
    session_start();
}

class CartItem {
    private $code;
    private $count;

    function __construct($code, $count) {
        $this->code = $code;
        $this->count = $count;
    }

    function getCode(){
        return $this->code;
    }

    function getCount(){
        return $this->count;
    }

    function setCount($count){
        $this->count = $count;
    }
}

class Cart {
    private $items;

    function __construct(){
        $this->items = array();
    }

    function getItem($n){
        return $this->items[$n];
    }

    function getItems(){
        return $this->items;
    }

    function getCount(){
        return count($this->items);
    }

    function addItem($code, $count){
        $this->items[] = new CartItem($code, $count);
    }

    function removeItem($n){
        array_splice($this->items, $n, 1);
    }
}

if(!isset($_SESSION["cart"])){
    $_SESSION["cart"] = new Cart();
}
