<?php

namespace App\Models;

class Cart
{
    public $items = [];
    public $totalQty = 0;
    public $totalPrice = 0;

    public function __construct($oldCart = null)
    {
        if (! $oldCart) {
            return;
        }

        if (is_array($oldCart)) {
            $this->items = $oldCart['items'] ?? [];
            $this->totalQty = $oldCart['totalQty'] ?? 0;
            $this->totalPrice = $oldCart['totalPrice'] ?? 0;

            // Ensure internal items are objects if they were restored as arrays from session
            foreach ($this->items as $id => $item) {
                if (isset($item['item']) && is_array($item['item'])) {
                    $this->items[$id]['item'] = (object) $item['item'];
                }
            }
            return;
        }

        $this->items = $oldCart->items ?? [];
        $this->totalQty = $oldCart->totalQty ?? 0;
        $this->totalPrice = $oldCart->totalPrice ?? 0;
    }

    public function add($item, $id)
    {
        $storedItem = [
            'qty' => 0,
            'price' => 0,
            'item' => $item,
        ];

        if (isset($this->items[$id])) {
            $storedItem = $this->items[$id];
        }

        $storedItem['qty']++;

        $unitPrice = $this->getUnitPrice($item);
        $storedItem['price'] = $unitPrice * $storedItem['qty'];

        $this->items[$id] = $storedItem;
        $this->totalQty++;
        $this->totalPrice += $unitPrice;
    }

    public function removeItem($id)
    {
        if (!isset($this->items[$id])) {
            return;
        }

        $this->totalQty -= $this->items[$id]['qty'];
        $this->totalPrice -= $this->items[$id]['price'];
        unset($this->items[$id]);
    }

    public function updateQty($id, $qty)
    {
        if (!isset($this->items[$id])) {
            return;
        }

        $item = $this->items[$id];
        $unitPrice = $this->getUnitPrice($item['item']);

        $this->totalQty -= $item['qty'];
        $this->totalPrice -= $item['price'];

        $item['qty'] = $qty;
        $item['price'] = $unitPrice * $qty;

        $this->items[$id] = $item;
        $this->totalQty += $qty;
        $this->totalPrice += $item['price'];
    }

    private function getUnitPrice($item)
    {
        if (isset($item->sale_price) && $item->sale_price > 0) {
            return $item->sale_price;
        }

        return $item->price;
    }
}
