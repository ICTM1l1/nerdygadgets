<?php
if(session_status() == PHP_SESSION_NONE) {
    session_start();
}

class Cart {
    private array $items;
    private float $cost;
    private bool $updated;

    function __construct(){
        $this->items = array();
        $this->updated = true;
    }

    function getItemCount($id): int{
        if(isset($this->items[$id])){
            return $this->items[$id];
        }
        return 0;
    }

    function setItemCount($id, $count): void{
        if(isset($this->items[$id])){
            $this->items[$id] = $count;
            $this->updated = true;
        }
    }

    function getItems(): array{
        $i = array();
        foreach($this->items as $id => $count){
            $i[] = array("id" => $id, "amount" => $count);
        }
        return $i;
    }

    function getCount(): int{
        return count($this->items);
    }

    function addItem($id, $count): void{
        if(!isset($this->items[$id])){
            $this->items += array($id => $count);
            $this->updated = true;
        }
    }

    function removeItem($id): void{
        if(isset($this->items[$id])) {
            unset($this->items[$id]);
            $this->updated = true;
        }
    }

    function cleanCart(): void{
        $this->items = array();
        $this->updated = true;
    }

    function getTotalPrice(): float{
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

/*if(!isset($_SESSION["cart"])){
    $_SESSION["cart"] = new Cart();
}*/
