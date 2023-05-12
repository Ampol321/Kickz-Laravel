<?php

namespace App;

class Cart
{
    public $products = null;
    public $totalqty = 0;
    public $totalprice = 0;
    public function __construct($oldCart)
    {
        if($oldCart) {
            $this->products = $oldCart->products;
            $this->totalqty = $oldCart->totalqty;
            $this->totalprice = $oldCart->totalprice;
        }
    }
}
