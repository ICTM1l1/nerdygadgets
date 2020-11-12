<?php
session_start();

class CartItem {
    private $code;
    private $count;

    function __construct($code, $count) {
        $this->code = $code;
        $this->count = $count;
    }

    function get_code(){
        return $this->code;
    }

    function get_count(){
        return $this->count;
    }

    function set_count($count){
        $this->count = $count;
    }
}

class Cart {
    private $items;

    function __construct(){
        $this->items = array();
    }

    function get_item($n){
        return $this->items[$n];
    }

    function get_items(){
        return $this->items;
    }

    function get_count(){
        return count($this->items);
    }

    function add_item($code, $count){
        $this->items[] = CartItem($code, $count);
    }

    function remove_item($n){
        array_splice($this->items, $n, 1);
    }
}