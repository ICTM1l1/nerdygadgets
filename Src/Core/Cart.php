<?php

class Cart {
    private array $items;
    private float $cost;
    private bool $updated;

    function __construct(){
        $this->items = array();
        $this->updated = true;
    }

    function getItemCount(int $id): int{
        if(isset($this->items[$id])){
            return $this->items[$id];
        }
        return 0;
    }

    function setItemCount(int $id, int $count): void{
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

    function addItem(int $id, int $count): void{
        if(!isset($this->items[$id])){
            $this->items += array($id => $count);
            $this->updated = true;
        }
    }

    function removeItem(int $id): void{
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
            $total += getProduct($id)["SellPrice"] * $count;
        }
        $this->updated = false;
        $this->cost = $total;
        return $total;
    }
}
