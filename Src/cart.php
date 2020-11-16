<?php
if(session_status() == PHP_SESSION_NONE) {
    session_start();
}

include_once __DIR__ . "/../Src/Core/core.php";

class Cart {
    private $items;
    private $cost;
    private $updated;

    function __construct(){
        $this->items = array();
        $updated = true;
    }

    function getItemCount($id){
        if(array_key_exists($id, $this->items)){
            return $this->items[$id];
        }
        return 0;
    }

    function setItemCount($id, $count){
        if(array_key_exists($id, $this->items)){
            $this->items[$id] = $count;
        }
    }

    function getItems(){
        $i = array();
        foreach($this->items as $id => $count){
            $i[] = array("id" => $id, "amount" => $count);
        }
        return $i;
    }

    function getCount(){
        return count($this->items);
    }

    function addItem($id, $count){
        if(!array_key_exists($id, $this->items)){
            $this->items += array($id => $count);
        }
        $this->updated = true;
    }

    function removeItem($id){
        unset($this->items[$id]);
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
        foreach($this->items as $id => $count){
            $total += selectFirst("SELECT UnitPrice * :count AS total FROM stockitems WHERE StockItemId = :id",
                                  ["count" => $count, "id" => $id])["total"];
        }
        $this->updated = false;
        $this->cost = $total;
        return $total;
    }
}

if(!isset($_SESSION["cart"])){
    $_SESSION["cart"] = new Cart();
}
