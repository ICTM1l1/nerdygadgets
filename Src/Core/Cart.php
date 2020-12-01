<?php

/**
 * Provides a class for cart functionality.
 */
class Cart {

    /**
     * The items of the cart.
     *
     * @var array
     */
    private array $items;

    /**
     * The total costs of the items.
     *
     * @var float
     */
    private float $cost;

    /**
     * Whether the update was successful or not.
     *
     * @var bool
     */
    private bool $updated;

    /**
     * Cart constructor.
     */
    public function __construct() {
        $this->items = array();
        $this->updated = true;
    }

    /**
     * Gets the amount of the item.
     *
     * @param int $id
     *   The id of the item.
     *
     * @return int
     *   The amount of the item.
     */
    public function getItemCount(int $id): int {
        return $this->items[$id] ?? 0;
    }

    /**
     * Sets the amount of an item.
     *
     * @param int $id
     *   The id of the item.
     * @param int $count
     *   The amount of the item.
     */
    public function setItemCount(int $id, int $count): void {
        if(isset($this->items[$id])){
            $this->items[$id] = $count;
            $this->updated = true;
        }
    }

    /**
     * Increase the amount of an item.
     *
     * @param int $id
     *   The id of the item.
     */
    public function increaseItemCount(int $id): void {
        if(isset($this->items[$id])){
            $this->items[$id]++;
            $this->updated = true;
        }
    }

    /**
     * Decrease the amount of an item.
     *
     * @param int $id
     *   The id of the item.
     */
    public function decreaseItemCount(int $id): void {
        if(isset($this->items[$id])){
            $this->items[$id]--;
            $this->updated = true;
        }
    }

    /**
     * Gets the items.
     *
     * @return array
     *   The items.
     */
    public function getItems(): array {
        $items = array();
        foreach($this->items as $id => $count){
            $items[] = array("id" => $id, "amount" => $count);
        }

        return $items;
    }

    /**
     * Gets the counted items.
     *
     * @return int
     *   The counted items.
     */
    public function getCount(): int {
        return count($this->items);
    }

    /**
     * Adds an item to the cart.
     *
     * @param int $id
     *   The id of the item.
     * @param int $count
     *   The amount of the item.
     */
    public function addItem(int $id, int $count): void{
        if(!isset($this->items[$id])){
            $this->items += array($id => $count);
            $this->updated = true;
        }
    }

    /**
     * Removes an item from the cart.
     *
     * @param int $id
     *   The id of the item.
     */
    public function removeItem(int $id): void{
        if(isset($this->items[$id])) {
            unset($this->items[$id]);
            $this->updated = true;
        }
    }

    /**
     * Clears the cart.
     */
    public function cleanCart(): void{
        $this->items = array();
        $this->updated = true;
    }

    /**
     * Gets the total price.
     *
     * @return float
     *   The total price or the calculated one.
     */
    public function getTotalPrice(): float{
        if(!$this->updated){
            return $this->cost;
        }

        $total = 0;
        foreach($this->items as $id => $count){
            $product_price = getProduct($id)['SellPrice'] ?? 0;

            $total += $product_price * $count;
        }

        $this->updated = false;
        $this->cost = $total;

        return $total;
    }
}
