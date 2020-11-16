<?php
if(session_status() == PHP_SESSION_NONE) {
    session_start();
}

include_once __DIR__ . "/../Src/Core/core.php";

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
    private $cost;
    private $updated;

    function __construct(){
        $this->items = array();
        $updated = true;
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
        $this->updated = true;
    }

    function removeItem($n){
        array_splice($this->items, $n, 1);
        $this->updated = true;
    }

    function cleanCart(){
        $this->items = array();
        $this->updated = true;
    }

    function getTotalPrice(){
        if(!$this->updated){
            return $this->cost;
        }
        $total = 0;
        foreach($this->items as $item){
            $total += selectFirst("SELECT UnitPrice * :count AS total FROM stockitems WHERE StockItemId = :id",
                                  ["count" => $item->getCount(), "id" => $item->getCode()])["total"];
        }
        $this->updated = false;
        $this->cost = $total;
        return $total;
    }
}

if(!isset($_SESSION["cart"])){
    $_SESSION["cart"] = new Cart();
}
