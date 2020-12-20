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

        saveCart($this);
    }

    /**
     * Increase the amount of an item.
     *
     * @param int $id
     *   The id of the item.
     */
    public function increaseItemCount(int $id): void {
        $product = getProduct($id);
        $current_quantity = (int) ($product['QuantityOnHandRaw'] ?? 0);
        $count = $this->items[$id] ?? 1;
        if (($current_quantity - $count - 1) < 0) {
            add_user_error('Product ' . ($product['StockItemName'] ?? '') . ' is na verhoging van het aantal niet meer op voorraad');
            $this->updated = false;
            return;
        }

        if(isset($this->items[$id])){
            $this->items[$id]++;
            $this->updated = true;
        }

        if ($this->isUpdated()) {
            add_user_message('Product aantal is succesvol bijgewerkt.');
        }

        saveCart($this);
    }

    /**
     * Decrease the amount of an item.
     *
     * @param int $id
     *   The id of the item.
     */
    public function decreaseItemCount(int $id): void {
        if(isset($this->items[$id]) && $this->getItemCount($id) > 1){
            $this->items[$id]--;
            $this->updated = true;
        }else{
            $this->updated = false;
        }

        if ($this->isUpdated()) {
            add_user_message('Product aantal is succesvol bijgewerkt.');
        }

        saveCart($this);
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
            $items[] = array('id' => $id, 'amount' => $count);
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
    public function addItem(int $id, int $count = 1): void {
        $product = getProduct($id);
        $current_quantity = (int) ($product['QuantityOnHandRaw'] ?? 0);
        if (($current_quantity - $count) < 0) {
            add_user_error('Product ' . ($product['StockItemName'] ?? '') . ' is niet op voorraad');
            $this->updated = false;
            return;
        }

        if (!isset($this->items[$id])) {
            $this->items += array($id => $count);
            $this->updated = true;
        }

        if ($this->isUpdated()) {
            add_user_message('Product ' . ($product['StockItemName'] ?? '') . ' is toegevoegd aan de winkelwagen.');
        }

        saveCart($this);
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

        if ($this->isUpdated()) {
            add_user_message('Product is succesvol verwijderd uit de winkelwagen.');
        }

        saveCart($this);
    }

    /**
     * Clears the cart.
     */
    public function cleanCart(): void{
        $this->items = array();
        $this->updated = true;

        saveCart($this);
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
        saveCart($this);

        return $total;
    }

    /**
     * Determines whether the cart is updated or not.
     *
     * @return bool
     *   If the cart has been updated.
     */
    public function isUpdated(): bool {
        return $this->updated;
    }
}

/**
 * Saves the cart into the session.
 *
 * @param Cart $cart
 *   The cart.
 */
function saveCart(Cart $cart) {
    session_save('cart', serialize($cart), true);
}

/**
 * Resets and saves the cart into the session.
 */
function resetCart() {
    session_save('cart', serialize(new Cart()), true);
}

/**
 * Gets the cart.
 *
 * @return Cart
 *   The cart.
 */
function getCart() {
    $cart = $_SESSION['cart'] ?? null;
    if (!$cart) {
        $cart = new Cart();
        saveCart($cart);
        $cart = serialize($cart);
    }

    return unserialize($cart, [Cart::class]);
}